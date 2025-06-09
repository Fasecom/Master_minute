<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'date' => 'datetime',
        'cash_revenue' => 'decimal:2',
        'cashless_revenue' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }
} 