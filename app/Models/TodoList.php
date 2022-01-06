<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    public function user() //FIXME: Returns what?
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tasks() //FIXME: Returns what?!?!?!
    {
        return $this->hasMany(Task::class, 'list_id', 'id');
    }

    // https://stackoverflow.com/a/5438778
    private static function generateUUID(): string
    {
        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_";
        $base = strlen($charset);
        $result = '';

        $now = explode(' ', microtime())[1];
        while ($now >= $base) {
            $i = $now % $base;
            $result = $charset[$i] . $result;
            $now /= $base;
        }
        return substr($result, -5);
    }

    protected static function boot(): never
    {
        parent::boot();

        // Set uuid and user id values when item is created
        static::creating(function ($query) {
            $query->uuid = self::generateUUID();
            $query->user_id = Auth::user()->id;
        });
    }
}
