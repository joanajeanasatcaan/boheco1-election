<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
        return [
            'FirstName'      => 'required|string|max:255',
            'MiddleName'     => 'nullable|string|max:255',
            'LastName'       => 'required|string|max:255',
            'Suffix'         => 'nullable|string|max:50',
            'Gender'         => 'required|in:Male,Female',
            'BirthDate'      => 'required|date',
            'Sitio'          => 'required|string|max:255',
            'Barangay'       => 'required|string|max:255',
            'Town'           => 'required|string|max:255',
            'ContactNumbers' => 'required|string|max:50',
            'EmailAddress'   => 'required|email|unique:CRM_MemberConsumers,EmailAddress',
        ];
    }
}
