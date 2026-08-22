<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  protected $table = 'User';
protected $primaryKey = 'UserID';

public $timestamps = false;

protected $fillable = [
    'Username',
    'PasswordHash',
    'Email',
    'Phone',
    'RoleID',
    'IsActive',
    'DateBirth',
    'Position',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
    'PasswordHash',
    'remember_token',
];

public function getAuthPassword()
{
    return $this->PasswordHash;
}
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
}

