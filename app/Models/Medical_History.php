<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medical_History extends Model
{
    use HasFactory;

    protected $casts = [
        'medicine' => 'array'
    ];
    protected $fillable = [
        'disease',
        'date',
        'medicine',
        'patient_id',
        
    ];
}