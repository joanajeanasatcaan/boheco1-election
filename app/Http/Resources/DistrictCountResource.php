<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $completeDistricts = "District " . $this->district;
        return [
            'district'    => $completeDistricts,
            'votes_count' => $this->votes_count,
        ];
    }
}
