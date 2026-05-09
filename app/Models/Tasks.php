<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory, SoftDeletes;

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
}