<?php

namespace App\Http\Requests\API;



class LoginRequest extends APIRequest
{
    public function authorize()
    {
       // $this->isActive();
        return true;
    }
    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {

        return [
            'email' => 'required|email',
            'password' => 'required|min:8|max:32',
        ];
    }
}
