<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shortener extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'shorteners';
    protected $fillable = [
        'url',
        'slug',
        'expiration_date'
    ];
}
