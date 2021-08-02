<?php

namespace App\Http\Requests\API;



class RoleRequest extends APIRequest
{
    /**
     * Get the validation rules that apply to the get request.
     *
     * @return array
     */
    public function view()
    {

        $this->hasRole('superadmin|admin');
        $this->hasPermission('roles-read');
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
        $this->hasPermission('roles-create');

        return [
            'name' => 'required|min:3|max:128|unique:roles,name',

            'permissions' => 'required|array|min:1',
            'permissions.*.name' => 'required|string|distinct|min:3|max:32|exists:permissions,name',

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
        $this->hasPermission('roles-update');

        $rules = [
            'name' => 'sometimes|min:3|max:128',
        ];
        $rules['name'] = $rules['name'] . "," . $this->route("role_id");
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
        $this->hasPermission('roles-delete');
        return [
            //
        ];
    }
}
