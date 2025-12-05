<?php

namespace App\Http\Requests\Api\v1\WorkingHour;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\Common;

class UpdateWorkingHourRequest extends FormRequest
{
    use Common;
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
            'data' => 'required|array',
            'data.*.weekday' => 'required|integer|min:0|max:6',
            'data.*.is_closed' => 'required|in:1,2',
            'data.*.open_time' => 'nullable|required_if:data.*.is_closed,2|date_format:H:i:s',
            'data.*.close_time' => 'nullable|required_if:data.*.is_closed,2|date_format:H:i:s|after:data.*.open_time',
            'slot_duration' => 'required|integer|min:1|max:1440',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If data is sent as raw JSON body, convert it to data field
        if ($this->isJson() && empty($this->input('data'))) {
            $jsonData = $this->json()->all();
            if (is_array($jsonData)) {
                $this->merge([
                    'data' => $jsonData
                ]);
            }
        } elseif ($this->has('data') && is_string($this->input('data'))) {
            // If data is a JSON string, decode it
            $decoded = json_decode($this->input('data'), true);
            if (is_array($decoded)) {
                $this->merge([
                    'data' => $decoded
                ]);
            }
        }
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
        $errorMessage = $validator->errors()->first();

        throw new HttpResponseException( self::fail([], $errorMessage));
    }
}
