<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar_url' => $this->google_avatar_url ?? $this->github_avatar_url,
            'github' => [
                'connected' => $this->github_id !== null,
                'username' => $this->github_username,
                'avatar_url' => $this->github_avatar_url,
            ],
            'google' => [
                'connected' => $this->google_id !== null,
                'avatar_url' => $this->google_avatar_url,
            ],
        ];
    }
}
