<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'type'        => $this->type,
            'description' => $this->description,
            'created_at'  => $this->created_at->format('M j, Y H:i'),

            'member' => [
                'id'   => $this->member?->Id,
                'name' => trim(
                    ($this->member?->FirstName ?? '') . ' ' .
                    ($this->member?->MiddleName ?? '') . ' ' .
                    ($this->member?->LastName ?? '')
                ),
            ],

            'performed_by' => $this->performedBy ? [
                'id'   => $this->performedBy->id,
                'name' => $this->performedBy->name,
            ] : null,
        ];
    }
}
