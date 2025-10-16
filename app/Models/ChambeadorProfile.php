<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChambeadorProfile extends Model
{
    protected $fillable = [
        'uid',
        'status',
        'balance',
        'name',
        'last_name',
        'profession',
        'birth_date',
        'phone',
        'email',
        'gender',
        'address',
        'lat',
        'lng',
        'profile_image',
        'about_me',
        'skills',
        'category',
        'subcategories',
    ];

    protected $casts = [
        'skills' => 'array',
        'subcategories' => 'array',
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}
