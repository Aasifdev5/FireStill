<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'latitude', 'longitude'];

    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class);
    }
}
