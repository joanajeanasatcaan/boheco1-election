<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'FirstName'      => 'sometimes|required|string|max:255',
            'MiddleName'     => 'nullable|string|max:255',
            'LastName'       => 'sometimes|required|string|max:255',
            'Suffix'         => 'nullable|string|max:50',
            'Gender'         => 'sometimes|required|in:Male,Female',
            'BirthDate'      => 'sometimes|required|date',
            'Sitio'          => 'sometimes|required|string|max:255',
            'Barangay'       => 'sometimes|required|string|max:255',
            'Town'           => 'sometimes|required|string|max:255',
            'ContactNumbers' => 'sometimes|required|string|max:50',
            'EmailAddress'   => "sometimes|required|email|unique:CRM_MemberConsumers,EmailAddress,{$id},Id",
        ];
    }
}
