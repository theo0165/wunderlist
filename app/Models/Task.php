<?php

declare(strict_types=1);

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /** @return BelongsTo  */
    public function todoList(): BelongsTo
    {
        return $this->belongsTo(TodoList::Class, 'list_id', 'id');
    }

    // https://stackoverflow.com/a/5438778
    /** @return string  */
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
        });
    }

    /** @return BelongsTo  */
    public function list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class, 'list_id', 'id');
    }
}
