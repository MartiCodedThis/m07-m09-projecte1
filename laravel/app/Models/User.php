<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function places(){
        return $this->hasMany(Place::class, 'author_id');
    }

    public function liked(){
        return $this->belongsToMany(User::class, 'likes');
    }
    
    public function favorites(){
        return $this->belongsToMany(Place::class, 'favorites');
    }

    public function canAccessFilament() : bool
    {
        return $this->role_id === 2 || $this->role_id === 3;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class, 'user_id');
    }

}
