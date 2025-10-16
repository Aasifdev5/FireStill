<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $table = 'equipments';

    protected $fillable = [
        'client_id',
        'code',
        'qr_code',
        'type',
        'location',
        'status',
        'agent_type',
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
}
