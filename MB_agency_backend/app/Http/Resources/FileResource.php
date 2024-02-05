<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'uuid' => $this->uuid,
            'user_id' => $this->user_id,
            'user' => RoleResource::make($this->whenLoaded('user')),
            'created_at' => Carbon::parse($this->created_at)->toDateString(),

        ];
    }
}
