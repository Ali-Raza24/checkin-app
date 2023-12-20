<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class EmployeeRequest extends FormRequest
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
            'name' => 'bail|required|string|max:255',
            'gender' => 'bail|in:male,female,other',
            'email' => 'bail|required|email:rfc,dns|unique:employees,email',
            'dob' => 'bail|nullable|date|date_format:Y-m-d',
            'join_date' => 'bail|nullable|date',
            'probation_period' => 'bail|nullable|string|max:255',
            'designation' => 'bail|nullable|string|max:255',
            'line_manager' => 'bail|nullable|string|max:255',
            'contact_number' => 'bail|required|string|max:20|regex:/^\+?\d{1,15}(?:\s\d{1,15})*$/',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'contact_number.required' => 'The contact number field is required.',
            'name.max' => 'The name may not be greater than :max characters.',
            'gender.in' => 'The gender must be one of: male, female, other.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email address has already been taken.',
            'dob.date' => 'The dob must be a valid date.',
            'dob.date_format' => 'The date of birth must be in the format yyyy-mm-dd.',
            'join_date.date' => 'The join date must be a valid date.',
            'probation_period.max' => 'The probation period may not be greater than :max characters.',
            'designation.max' => 'The designation may not be greater than :max characters.',
            'line_manager.max' => 'The line manager may not be greater than :max characters.',
            'contact_number.max' => 'The contact number may not be greater than :max characters.',
            'contact_number.regex' => 'The contact number must be a valid phone number.',
        ];
    }


    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'success' => false,
            'errors' => $validator->errors(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
