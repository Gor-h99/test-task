<?php

namespace App\Http\Requests;

use App\Constants\ResponseConstants;
use App\Support\ApiResponder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @property string $date;
 */
class CheckStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => [
                'required',
                'string',
                'date',
                'after_or_equal:today',
            ],
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first('date');
        if (!$message) $message = 'The given data is invalid';
        ApiResponder::throwError($message, null, 422, ResponseConstants::VALIDATION_ERROR);
    }
}
