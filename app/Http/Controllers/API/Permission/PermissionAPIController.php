<?php

namespace App\Http\Controllers\API\Role;


use App\Facades\PermissionService;


use App\Http\Resources\PermissionResource;
use App\Http\Requests\API\PermissionRequest;
use App\Http\Controllers\API\APIController;

use App\Http\Resources\PermissionResourceCollection;

class PermissionAPIController extends APIController
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
    public function index(PermissionRequest $request)
    {

        $roles = PermissionService::query(
            $request->get('skip'),
            $request->get('limit')
        );


        return $this->sendResponse(
            new PermissionResourceCollection($roles),
            __('messages.retrieved', ['model' => __('models/roles.plural')])
        );
    }
    /**
     * Register a new user
     *
     * @param PermissionRequest $request
     * @return Model
     */
    public function create(PermissionRequest $request)
    {
        $input = $request->except(['display_names', 'descriptions', 'permissions']);

        $displayNames = $request->get('display_names');
        $descriptions = $request->get('descriptions');

        $role = PermissionService::save($input, $displayNames, $descriptions);

        return $this->sendResponse(
            new PermissionResource($role),
            __('messages.saved', ['model' => __('models/roles.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param PermissionRequest $request
     * @return Model
     */
    public function show($id, PermissionRequest $request)
    {

        $role = PermissionService::find($id);

        return $this->sendResponse(
            new PermissionResource($role),
            __('messages.retrieved', ['model' => __('models/roles.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param PermissionRequest $request
     * @return Model
     */
    public function update($id, PermissionRequest $request)
    {

        $input = $request->except('avatar');
        $avatar = $request->file('avatar');


        $role = PermissionService::update($id, $input, $avatar);

        return $this->sendResponse(
            new PermissionResource($role),
            __('messages.saved', ['model' => __('models/roles.singular')])
        );
    }




    /**
     * Delete a new user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, PermissionRequest $request)
    {
        $qtdDel = PermissionService::delete($id);

        return $this->sendSuccess(
            __('messages.deleted', ['model' => __('models/roles.singular')])
        );
    }
}
