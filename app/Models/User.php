<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'patron_id',
        'role',
        'department',
        'position',
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'ext_name',
        'access',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'access' => 'array',
    ];

    /**
     * Retrieve the username column name.
     *
     * @return string
     */
    public function username(): string
    {
        return 'username';
    }

    /**
     * Find user by username.
     *
     * @param string $username
     * @return mixed
     */
    public function findForIdentifier(string $username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
