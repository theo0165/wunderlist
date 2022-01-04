<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profilePicture()
    {
        return ($this->profile_picture) ? $this->profile_picture : "https://eu.ui-avatars.com/api/?size=200&name=" . $this->name;
    }

    public function lists()
    {
        return $this->hasMany(TodoList::class, 'user_id', 'id');
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, TodoList::class, 'user_id', 'list_id', 'id', 'id');
    }
}
