<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Diagnosis extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['disease_name', 'confidence'];
}