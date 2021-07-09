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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // return match($this->method()){
        //     'POST' => $this->store(),
        //     'PUT', 'PATCH' => $this->update(),
        //     'DELETE' => $this->destroy(),
        //     default => $this->view()
        // }

        switch ($this->method()) {
            case 'POST':
                return $this->store();

            case 'PUT':
            case 'PATCH':
                return $this->update();

            case 'DELETE':
                return $this->destroy();

            default:
                return $this->view();
        }
    }

    /**
     * Get the validation rules that apply to the get request.
     *
     * @return array
     */
    public function view()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    public function update()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the delete request.
     *
     * @return array
     */
    public function destroy()
    {
        return [
            //
        ];
    }





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
