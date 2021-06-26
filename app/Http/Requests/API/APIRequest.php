<?php

namespace App\Http\Requests\API;

use App\Traits\ApiCheckPermission;
use Exception;
use Illuminate\Support\Arr;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class APIRequest extends FormRequest
{
    use ApiResponser;
    use ApiCheckPermission;
    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $message = __('messages.given_data_was_invalid');
        return $this->sendErrorUnprocessable($errors, $message);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $message = __('messages.given_data_was_invalid');

        $response = $this->sendErrorUnprocessable($errors, $message);

        throw new HttpResponseException($response);
    }
}
