<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return $this->role === $roles;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function isMaintenance()
    {
        return $this->role === 'maintenance';
    }

    public function isInkoop()
    {
        return $this->role === 'inkoop';
    }

    public function isSales()
    {
        return $this->role === 'sales';
    }

    public function isFinance()
    {
        return $this->role === 'finance';
    }

    public function isInPurchaseDepartment(): bool
    {
        return $this->department && strtolower($this->department->name) === 'purchase';
    }

    public function canAccessPurchase(): bool
    {
        return $this->hasAnyRole(['inkoop', 'manager', 'admin']) ||
               $this->isInPurchaseDepartment();
    }

    public function canAccessSales(): bool
    {
        return $this->hasAnyRole(['sales', 'manager', 'admin']) ||
               ($this->department && strtolower($this->department->name) === 'sales');
    }

    public function canAccessFinance(): bool
    {
        return $this->hasAnyRole(['finance', 'manager', 'admin']) ||
               ($this->department && strtolower($this->department->name) === 'finance');
    }

    public function canAccessMaintenance(): bool
    {
        return $this->hasAnyRole(['maintenance', 'manager', 'admin']) ||
               ($this->department && strtolower($this->department->name) === 'maintenance');
    }
}
