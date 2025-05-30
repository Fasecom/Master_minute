<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $table = 'workshops';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'open_date',
        'close_date',
        'open_time',
        'close_time',
    ];
} 