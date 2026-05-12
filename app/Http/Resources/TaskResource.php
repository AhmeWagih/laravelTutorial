<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'priority' => $this->priority,
            'status' => $this->status,
            'board_column' => $this->board_column,
            'creator_id' => $this->creator_id,
            'assignee_id' => $this->assignee_id,
            'creator' => new UserResource($this->whenLoaded('creator')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
