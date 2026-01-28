<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoterVerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $verification = $this->resource;
        $member = $verification->member;

        return [
            'message' => 'Household verified successfully.',

            'member' => $member ? [
                'id'   => (string) $member->Id,
                'name' => $member->FullName,
            ] : null,

            'spouse' => $member && $member->spouse ? [
                'id'   => (string) $member->spouse->id,
                'name' => $member->spouse->FullName,
            ] : null,

            'verified_at' => $verification->verified_at,
            'is_verified' => (bool) $verification->is_verified,
        ];
    }
}

