<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'id_number',
        'front_image',
        'back_image',
    ];
}
