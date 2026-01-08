<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ResponseHelper;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'department_id' => 'sometimes|required|exists:departments,id',
            'position_id' => 'sometimes|required|exists:positions,id',
            'status' => [
                'sometimes',
                'nullable',
                Rule::in(['active', 'probation', 'intern', 'contract']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employee name is required.',
            'name.string'   => 'Employee name must be a text.',
            'name.max'      => 'Employee name maximum 255 characters.',

            'department_id.required' => 'Department is required.',
            'department_id.exists'   => 'Department not found.',

            'position_id.required' => 'Position is required.',
            'position_id.exists'   => 'Position not found.',

            'status.in' => 'Status "resigned" is not allowed in this request.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $firstMessage = collect($validator->errors()->all())->first();

        throw new HttpResponseException(
            ResponseHelper::apiError($firstMessage, null, 422)
        );
    }
}
