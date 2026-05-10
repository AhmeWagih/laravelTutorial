<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    // this is important for the seeds
    protected $fillable = [
        'title',
        'description',
        'creator_id',
        'assignee_id',
        'creator',
        'assigned_to',
        'due_date',
        'priority',
        'status',
        'board_column',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function taskComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function taskImages(): HasMany
    {
        return $this->hasMany(TaskImage::class, 'task_id');
    }

    public function images(): HasMany
    {
        return $this->taskImages();
    }
}