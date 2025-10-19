<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Equipment extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'equipments';

    protected $fillable = [
        'client_id',
        'code',
        'qr_code',
        'inspection_qr_code', // Added for inspection history QR code
        'type',
        'brand',
        'model',
        'manufacturer_code',
        'serial_number',
        'location',
        'status',
        'agent_type',
        'manufacture_date',
        'last_recharge_date',
        'installation_date',
        'expiry_date',
        'notes',
    ];

    /**
     * Equipment belongs to a client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Equipment has many inspections.
     */
    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    /**
     * Get the latest inspection.
     */
    public function latestInspection()
    {
        return $this->hasOne(Inspection::class)->latestOfMany();
    }

    /**
     * Get the next inspection date (semiannual).
     */
    public function nextInspectionDate()
    {
        $latest = $this->latestInspection()->first();
        return $latest ? \Carbon\Carbon::parse($latest->inspected_at)->addMonths(6) : now();
    }

    /**
     * Determine if a reminder should be sent.
     */
    public function needsReminder()
    {
        $next = $this->nextInspectionDate();
        return $next->isFuture() && $next->diffInDays(now()) <= 30;
    }

    /**
     * Determine if equipment is critical.
     */
    public function isCritical()
    {
        $latest = $this->latestInspection()->first();
        return $latest && $latest->status === 'critical';
    }

    /**
     * Notify the client via email.
     */
    public function notifyClient(string $type)
    {
        if ($this->client && $this->client->email) {
            $nextInspection = $this->nextInspectionDate();
            $isCritical = $type === 'critical';
            $this->client->notify(new \App\Notifications\EquipmentInspectionReminder($this, $nextInspection, $isCritical));
        }
    }

    /**
     * Get the current status indicator.
     */
    public function getStatusIndicator()
    {
        if ($this->isCritical()) {
            return '🔴 Out of service';
        } elseif ($this->needsReminder()) {
            return '🟡 Needs maintenance';
        } else {
            return '🟢 Operational';
        }
    }

    /**
     * Get the equipment type display (e.g., "Powder Extinguisher").
     */
    public function getTypeDisplay()
    {
        $typeMap = [
            'extinguisher' => 'Extinguisher',
            'hydrant' => 'Hydrant',
            'hose' => 'Hose',
            'cabinet' => 'Cabinet',
        ];

        $baseType = $typeMap[$this->type] ?? ucfirst($this->type);
        return $this->agent_type ? $this->agent_type . ' ' . $baseType : $baseType;
    }
}
