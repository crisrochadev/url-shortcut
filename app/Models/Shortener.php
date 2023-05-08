<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortener extends Model
{
    use HasFactory;

    protected $table = 'shorteners';
    protected $fillable = ['url', 'slug', 'expiration_date'];
}
