<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by',
        'status',
        'date',
        'start_time',
        'end_time',
        'is_time_undefined',
        'location',
        'location_details',
        'latitude',
        'longitude',
        'category',
        'subcategory',
        'description',
        'budget',
        'payment_method',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function contract()
    {
        return $this->hasOne(Contract::class, 'service_request_id');
    }

    public function proposals()
    {
        return $this->hasMany(Proposal::class, 'service_request_id');
    }
}
