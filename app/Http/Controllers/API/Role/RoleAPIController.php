<?php

namespace App\Http\Controllers\API\Role;


use App\Facades\RoleService;


use App\Http\Resources\RoleResource;
use App\Http\Requests\API\RoleRequest;
use App\Http\Controllers\API\APIController;

use App\Http\Resources\RoleResourceCollection;

class RoleAPIController extends APIController
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * List all auth roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RoleRequest $request)
    {

        $roles = RoleService::query(
            $request->get('skip'),
            $request->get('limit')
        );


        return $this->sendResponse(
            new RoleResourceCollection($roles),
            __('messages.retrieved', ['model' => __('models/roles.plural')])
        );
    }
    /**
     * Register a new user
     *
     * @param RoleRequest $request
     * @return Model
     */
    public function create(RoleRequest $request)
    {
        $input = $request->except(['display_names', 'descriptions', 'permissions']);

        $displayNames = $request->get('display_names');
        $descriptions = $request->get('descriptions');
        $permissionsToAttach = $request->get('permissions');

        $role = RoleService::save($input, $displayNames, $descriptions, $permissionsToAttach);

        return $this->sendResponse(
            new RoleResource($role),
            __('messages.saved', ['model' => __('models/roles.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param RoleRequest $request
     * @return Model
     */
    public function show($id, RoleRequest $request)
    {

        $role = RoleService::find($id);

        return $this->sendResponse(
            new RoleResource($role),
            __('messages.retrieved', ['model' => __('models/roles.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param RoleRequest $request
     * @return Model
     */
    public function update($id, RoleRequest $request)
    {

        $input = $request->except('avatar');
        $avatar = $request->file('avatar');


        $role = RoleService::update($id, $input, $avatar);

        return $this->sendResponse(
            new RoleResource($role),
            __('messages.saved', ['model' => __('models/roles.singular')])
        );
    }




    /**
     * Delete a new user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, RoleRequest $request)
    {
        $qtdDel = RoleService::delete($id);

        return $this->sendSuccess(
            __('messages.deleted', ['model' => __('models/roles.singular')])
        );
    }
}
