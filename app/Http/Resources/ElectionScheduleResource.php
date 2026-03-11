<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ElectionScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
   public function toArray($request)
    {

        return [
            'id'             => $this->id,
            'scheduled_date' => $this->scheduled_date->format('Y-m-d'), // ✅ "2026-03-05"
            'district'       => $this->district,
            'is_active'      => $this->is_active,
            'created_by'     => $this->createdBy?->name,
            'created_at'     => $this->created_at->format('M j, Y'),
        ];
    }
}