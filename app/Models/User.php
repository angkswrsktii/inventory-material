<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'm_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    const ROLE_ADMIN         = 'admin';
    const ROLE_PIMPINAN      = 'pimpinan';
    const ROLE_KEPALA_GUDANG = 'kepala_gudang';
    const ROLE_KARYAWAN      = 'karyawan';

    public function isAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
    public function isPimpinan(): bool { return $this->role === self::ROLE_PIMPINAN; }
    public function isKepalaGudang(): bool { return $this->role === self::ROLE_KEPALA_GUDANG; }
    public function isKaryawan(): bool { return $this->role === self::ROLE_KARYAWAN; }

    public function isManagement(): bool {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PIMPINAN]);
    }

    public function canApprove(): bool {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PIMPINAN, self::ROLE_KEPALA_GUDANG]);
    }

    public function canApprovePR(): bool {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PIMPINAN]);
    }

    public function getRoleLabelAttribute(): string {
        return match($this->role) {
            self::ROLE_ADMIN         => 'Administrator',
            self::ROLE_PIMPINAN      => 'Pimpinan',
            self::ROLE_KEPALA_GUDANG => 'Kepala Gudang',
            self::ROLE_KARYAWAN      => 'Pegawai',
            default                  => ucfirst($this->role),
        };
    }
}