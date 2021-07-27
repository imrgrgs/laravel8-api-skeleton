<?php

namespace App\Http\Requests\API;



class UserRequest extends APIRequest
{
    /**
     * Get the validation rules that apply to the get request.
     *
     * @return array
     */
    public function view()
    {

        $this->hasRole('superadmin|admin');
        $this->hasPermission('users-read');
        return [];
    }
    /**
     * Get the validation rules that apply to the post request.
     *
     * @return array
     */
    public function store()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('users-create');

        return [
            'name' => 'required|min:3|max:128',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:32',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'roles' => 'required_without:permissions|array|min:1',
            'roles.*.name' => 'required_without:permissions|string|distinct|min:3|max:32|exists:roles,name',

            'permissions' => 'required_without:roles|array|min:1',
            'permissions.*.name' => 'required_without:roles|string|distinct|min:3|max:32|exists:permissions,name',

        ];
    }

    /**
     * Get the validation rules that apply to the put/patch request.
     *
     * @return array
     */
    public function update()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('users-update');

        $rules = [
            'name' => 'sometimes|min:3|max:128',
            'email' => 'sometimes|email|unique:users,email',
            'password' => 'sometimes|min:8|max:32',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
        $rules['email'] = $rules['email'] . "," . $this->route("user_id");
        return $rules;
    }

    /**
     * Get the validation rules that apply to the delete request.
     *
     * @return array
     */
    public function destroy()
    {
        $this->hasRole('superadmin|admin');
        $this->hasPermission('users-delete');
        return [
            //
        ];
    }
}
