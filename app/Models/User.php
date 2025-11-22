<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'birthdate',
        'gender',
        'contact_number',
        'address_line_1',
        'address_line_2',
        'region_id',
        'province_id',
        'municipality_id',
        'barangay_id',
        'is_active',
        'is_password_reset',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate'         => 'date',
        'is_password_reset' => 'boolean',
        'password' => 'hashed',
    ];

    public function initials(): string
    {
        return strtoupper(
            ($this->first_name[0] ?? '') .
            ($this->last_name[0] ?? '')
        );
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} " . ($this->middle_name ? "{$this->middle_name} " : '') . "{$this->last_name}");
    }
}
