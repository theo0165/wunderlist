<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'completed'
    ];

    public function todoList()
    {
        return $this->belongsTo(TodoList::Class);
    }
}
