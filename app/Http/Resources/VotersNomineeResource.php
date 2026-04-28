<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VotersNomineeResource extends JsonResource
{
    public function toArray($request)
    {
        // Read the image file and encode it as base64
        $imageBase64 = null;
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            $imageData = Storage::disk('public')->get($this->image_path);
            $mimeType  = Storage::disk('public')->mimeType($this->image_path);
            $imageBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }

        return [
            'id'          => $this->id,
            'first_name'  => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name'   => $this->last_name,
            'town'        => $this->town,
            'district'    => $this->district,
            'nickname'    => $this->nickname,
            'votes_count' => $this->votes()->distinct('household_id')->count('household_id'),
            'image_base64'=> $imageBase64,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}