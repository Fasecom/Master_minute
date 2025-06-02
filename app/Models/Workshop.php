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

    public function workingShifts()
    {
        return $this->hasMany(WorkingShift::class);
    }

    public function currentEmployee()
    {
        // Получаем смену на сегодня (берём первую найденную)
        $currentShift = $this->workingShifts()
            ->whereDate('date', date('Y-m-d'))
            ->first();

        return $currentShift ? $currentShift->user : null;
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_workshop', 'workshop_id', 'service_id');
    }
} 