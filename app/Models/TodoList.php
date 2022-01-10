<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Vinkla\Hashids\Facades\Hashids;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    /**
     * Eloquent relation that connects todo list to owner.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Eloquent relation that connects todo list with all child tasks
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'list_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        // Set uuid and user id values when item is created
        static::creating(function ($query) {
            $query->user_id = Auth::user()->id;
        });
    }

    /**
     * Get hased id for use in urls
     *
     * @return string
     */
    public function getHashId(): string
    {
        return Hashids::encode($this->id);
    }
}
