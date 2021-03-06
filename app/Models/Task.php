<?php

declare(strict_types=1);

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vinkla\Hashids\Facades\Hashids;

class Task extends Model
{
    use HasFactory;

    public $casts = [
        'completed' => 'boolean'
    ];

    public $fillable = [
        'title',
        'description',
        'deadline',
        'list_id',
        'completed'
    ];

    /**
     * Eloquent relationship that connects task to parent todo list
     *
     * @return BelongsTo
     */
    public function list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class, 'list_id', 'id');
    }

    /**
     * Get hased id for use in url
     *
     * @return string
     */
    public function getHashId(): string
    {
        return Hashids::encode($this->id);
    }
}
