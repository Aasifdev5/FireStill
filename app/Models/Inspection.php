<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'user_id',
        'inspected_at',
        'checklist',
        'observations',
        'status',
        'report_path',
        'expiration_date',
    ];

    protected $casts = [
        'checklist' => 'array',
        'inspected_at' => 'datetime',
        'expiration_date' => 'date',
    ];

    /**
     * Inspection belongs to an equipment.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Inspection belongs to a user (technician).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Inspection has many photos.
     */
    public function photos()
    {
        return $this->hasMany(InspectionPhoto::class);
    }
}
