<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    private const DEPARTMENT_ROLE_MAP = [
        1 => 'sales',
        2 => 'finance',
        3 => 'maintenance',
        4 => 'inkoop',
        5 => 'admin',
        6 => 'klantenservice',
    ];

    public static function roleForDepartment(?int $departmentId): ?string
    {
        $key = $departmentId ? (int) $departmentId : 0;

        return self::DEPARTMENT_ROLE_MAP[$key] ?? null;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Role methods
    public function getRoleAttribute($value)
    {
        $departmentId = $this->department_id !== null ? (int) $this->department_id : null;

        return self::roleForDepartment($departmentId) ?? $value;
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles, true);
        }

        return $this->role === $roles;
    }

    public function isAdmin()
    {
        return (int) $this->department_id === 5;
    }

    public function isManager()
    {
        return (int) $this->department_id === 5;
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isMaintenance()
    {
        return (int) $this->department_id === 3;
    }

    public function isInkoop()
    {
        return (int) $this->department_id === 4;
    }
}
