<?php

namespace App\Http\Requests\API;

use Exception;
use Illuminate\Http\JsonResponse;




class ListParamAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('params-list');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
