<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskImage extends Model
{
    use HasFactory;

    protected $table = 'tasks_images';

    protected $fillable = [
        'task_id',
        'path',
        'original_name',
    ];

    protected $appends = ['url'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }
}

