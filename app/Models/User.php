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
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'main_address',
        'profile_image',
        'domain_email',
        // Add any other fields that need to be mass assignable
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Define the username field
    public function username()
    {
        return 'username';
    }

    // Find user by username
    public function findForIdentifier($username)
    {
        return $this->where('username', $username)->first();
    }

    // Check if user has a certain role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Define relationship with favorites
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Define relationships for favorite items
    public function favoriteBooks()
    {
        return $this->hasMany(FavoriteBook::class);
    }

    public function favoritePeriodicals()
    {
        return $this->hasMany(FavoritePeriodical::class);
    }

    public function favoriteArticles()
    {
        return $this->hasMany(FavoriteArticle::class);
    }

    public function favoriteProjects()
    {
        return $this->hasMany(FavoriteProject::class);
    }
}
