<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'phone',
        'password',
        'role_id',
        'work_start_date',
        'work_end_date',
        'color',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'work_start_date' => 'date',
        'work_end_date' => 'date',
    ];

    /**
     * Get the login identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'phone';
    }

    public function workingShifts()
    {
        return $this->hasMany(WorkingShift::class);
    }

    public function skills()
    {
        return $this->belongsToMany(\App\Models\Skill::class, 'skill_user', 'user_id', 'skill_id');
    }

    /**
     * Get short representation of user name: "Фамилия И.О.".
     *
     * @return string
     */
    public function getShortNameAttribute(): string
    {
        if (empty($this->full_name)) {
            return $this->name ?? $this->email ?? '';
        }

        $parts = preg_split('/\s+/u', trim($this->full_name));
        $surname = $parts[0] ?? '';
        $firstInitial = isset($parts[1]) ? mb_substr($parts[1], 0, 1).'.' : '';
        $patronymicInitial = isset($parts[2]) ? mb_substr($parts[2], 0, 1).'.' : '';

        return trim(sprintf('%s %s%s', $surname, $firstInitial, $patronymicInitial));
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function (User $user) {
            // Автоматически назначаем цвет только мастерам (role_id = 3)
            if ($user->role_id == 3 && empty($user->color)) {
                $colors = config('master_colors');
                if (!empty($colors)) {
                    $index = User::where('role_id', 3)->whereNotNull('color')->count() % count($colors);
                    $user->color = $colors[$index];
                }
            }
        });
    }
}
