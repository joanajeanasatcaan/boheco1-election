<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MemberSpouse;
use App\Models\VoterVerification;
use Carbon\Carbon;
use App\Models\VoteLog;

class EcomListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $model = $this->resource;
 
        $isVerified   = false;
        $voterId      = null;
        $dateVerified = null;
        $remarks      = null;
        $voteDate     = null;
        $voteMethod   = null;
 
        if ($model instanceof \App\Models\Member) {
            $voterId = $model->Id;
        } elseif ($model instanceof \App\Models\MemberSpouse && $model->member) {
            $voterId = $model->member->Id;
        }
 
        if ($voterId) {
            $verification = VoterVerification::where('voter_id', $voterId)
                ->where('is_verified', true)
                ->first(['is_verified', 'verified_at', 'remarks']);
 
            $isVerified   = (bool) $verification;
            $dateVerified = $verification?->verified_at;
            $remarks      = $verification?->remarks;
 
            $voteLog    = VoteLog::where('member_id', $voterId)
                ->first(['created_at', 'voted_method']);
 
            $voteDate   = $voteLog?->created_at;
            $voteMethod = $voteLog?->voted_method;
        }
 
        if ($model instanceof \App\Models\Member) {
            return [
                'member_id'          => (string) $model->Id,
                'first_name'         => $model->FirstName,
                'middle_name'        => $model->MiddleName,
                'last_name'          => $model->LastName,
                'suffix'             => $model->Suffix,
                'district'           => $model->townDetail?->District,
                'gender'             => $model->Gender,
                'birth_date'         => Carbon::parse($model->Birthdate)->format('F d, Y'),
                'contact_number'     => $model->ContactNumbers,
                'email'              => $model->EmailAddress,
                'status'             => $isVerified,
                'address'            => $model->FullAddress,
                'date_verified_time' => $dateVerified ? Carbon::parse($dateVerified)->format('H:i:s') : null,
                'date_verified_day'  => $dateVerified ? Carbon::parse($dateVerified)->format('F d, Y') : null,
                'voted_date'         => $voteDate ? Carbon::parse($voteDate)->format('F d, Y') : null,
                'voted_time'         => $voteDate ? Carbon::parse($voteDate)->format('H:i:s') : null,
                'voted_method'       => $voteMethod,
                'remarks'            => $remarks,
 
                'spouse' => $model->spouse ? [
                    'id'        => (string) $model->spouse->id,
                    'full_name' => $model->spouse->FullName,
                ] : null,
            ];
        }
 
        if ($model instanceof \App\Models\MemberSpouse) {
            return [
                'id'             => (string) $model->id,
                'first_name'     => $model->FirstName,
                'middle_name'    => $model->MiddleName,
                'last_name'      => $model->LastName,
                'suffix'         => $model->Suffix,
                'district'       => $model->townDetail?->District,
                'gender'         => $model->Gender,
                'birth_date'     => $model->BirthDate,
                'contact_number' => $model->ContactNumbers,
                'email'          => $model->EmailAddress,
                'is_verified'    => $isVerified,
                'address'        => $model->FullAddress,
                'remarks'        => $remarks,
 
                'member' => $model->member ? [
                    'member_id' => (string) $model->member->Id,
                    'full_name' => $model->member->FullName,
                ] : null,
            ];
        }
 
        return [];
    }
}
 