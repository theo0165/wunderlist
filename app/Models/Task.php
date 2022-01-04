<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'description',
        'deadline',
        'list_id',
        'completed'
    ];

    public function todoList()
    {
        return $this->belongsTo(TodoList::Class, 'list_id', 'id');
    }

    // https://stackoverflow.com/a/5438778
    private static function generateUUID()
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

    protected static function boot()
    {
        parent::boot();

        // Set uuid and user id values when item is created
        static::creating(function ($query) {
            $query->uuid = self::generateUUID();
        });
    }

    public function list()
    {
        return $this->belongsTo(TodoList::class, 'list_id', 'id');
    }
}
