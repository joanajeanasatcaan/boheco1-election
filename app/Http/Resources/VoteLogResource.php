<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoteLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'nominee'       => [
                'id'        => $this->nominee->id,
                'full_name' => "{$this->nominee->first_name} {$this->nominee->last_name}",
                'nickname'  => $this->nominee->nickname,
                'image_url' => $this->nominee->image_path 
                                ? Storage::disk('public')->url($this->nominee->image_path)
                                : null,
            ],
            'member'        => [
                'id'        => $this->member_id,
                'full_name' => $this->member?->FullName ?? null,
                'household_id' => $this->household_id,
            ],
            'ip_address'    => $this->ip_address,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
