<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingShift extends Model
{
    use HasFactory;

    protected $table = 'working_shifts';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'workshop_id',
        'cash_revenue',
        'cashless_revenue',
        'date',
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 