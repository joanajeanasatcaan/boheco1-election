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
                'first_name' => $model->FirstName,
                'middle_name' => $model->MiddleName,
                'last_name' => $model->LastName,
                'suffix'    => $model->Suffix,
                'district'  => $model->townDetail?->District,
                'gender'    => $model->Gender,
                'birth_date'=> $model->BirthDate,
                'contact_number' => $model->ContactNumbers,
                'email'     => $model->EmailAddress,
                'is_verified' => $isVerified,

                'spouse' => $model->spouse ? [
                    'id'        => (string) $model->spouse->id,
                    'full_name' => $model->spouse->FullName
                ] : null,
            ];
        }

        if ($model instanceof \App\Models\MemberSpouse) {
            return [
                'id'        => (string) $model->id,
                'first_name' => $model->FirstName,
                'middle_name' => $model->MiddleName,
                'last_name' => $model->LastName,
                'suffix'    => $model->Suffix,
                'district'  => $model->townDetail?->District,
                'gender'    => $model->Gender,
                'birth_date'=> $model->BirthDate,
                'contact_number' => $model->ContactNumbers,
                'email'     => $model->EmailAddress,
                'is_verified' => $isVerified,

                'member' => $model->member ? [
                    'member_id' => (string) $model->member->Id,
                    'full_name' => $model->member->FullName,
                ] : null,
            ];
        }

        return [];
    }
}
