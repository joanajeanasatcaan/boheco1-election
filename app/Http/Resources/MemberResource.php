<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MemberSpouse;
use App\Models\VoterVerification;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $model = $this->resource; 

        $isVerified = false;
        $voterId = null;

        if ($model instanceof \App\Models\Member) {
            $voterId = $model->Id;
        } elseif ($model instanceof \App\Models\MemberSpouse && $model->member) {
            $voterId = $model->member->Id;
        }

        if ($voterId) {
            $isVerified = VoterVerification::where('voter_id', $voterId)
                ->where('is_verified', true)
                ->exists();
        }

        if ($model instanceof \App\Models\Member) {
            return [
                'member_id' => (string) $model->Id,
                'full_name' => $model->FullName,
                'address'   => $model->FullAddress,
                'gender'    => $model->Gender,
                'birth_date'=> $model->BirthDate,
                'contact_number' => $model->ContactNumbers,
                'email'     => $model->EmailAddress,
                'is_verified' => $isVerified,

                'spouse' => $model->spouse ? [
                    'id'        => (string) $model->spouse->id,
                    'full_name' => $model->spouse->FullName,
                    'address'   => $model->spouse->FullAddress,
                    'gender'    => $model->spouse->Gender,
                    'birth_date'=> $model->spouse->BirthDate,
                    'contact_number' => $model->spouse->ContactNumbers,
                    'email'     => $model->spouse->EmailAddress,
                ] : null,
            ];
        }

        if ($model instanceof \App\Models\MemberSpouse) {
            return [
                'id'        => (string) $model->id,
                'full_name' => $model->FullName,
                'address'   => $model->FullAddress,
                'gender'    => $model->Gender,
                'birth_date'=> $model->BirthDate,
                'contact_number' => $model->ContactNumbers,
                'email'     => $model->EmailAddress,
                'is_verified' => $isVerified,

                'member' => $model->member ? [
                    'member_id' => (string) $model->member->Id,
                    'full_name' => $model->member->FullName,
                    'address'   => $model->member->FullAddress,
                    'gender'    => $model->member->Gender,
                    'birth_date'=> $model->member->BirthDate,
                    'contact_number' => $model->member->ContactNumbers,
                    'email'     => $model->member->EmailAddress,
                ] : null,
            ];
        }

        return [];
    }
}
