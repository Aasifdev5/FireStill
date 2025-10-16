<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackgroundCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',          // Add the UID from Firebase or auth
        'certificate_path',    // Assuming this is what you store

    ];
}
