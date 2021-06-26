<?php

namespace App\Http\Controllers\API\Tenant;


use Illuminate\Http\Request;



use App\Facades\TenantService;
use App\Http\Resources\TenantResource;
use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\ListTenantAPIRequest;
use App\Http\Requests\API\ShowTenantAPIRequest;

use App\Http\Resources\TenantResourceCollection;
use App\Http\Requests\API\DeleteTenantAPIRequest;
use App\Http\Requests\API\RegisterUserAPIRequest;
use App\Http\Requests\API\UpdateTenantAPIRequest;
use App\Http\Requests\API\RegisterTenantAPIRequest;



class TenantAPIController extends APIController
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
     * List all auth users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListTenantAPIRequest $request)
    {
        $users = TenantService::query(
            $request->get('skip'),
            $request->get('limit')
        );


        return $this->sendResponse(
            new TenantResourceCollection($users),
            __('messages.retrieved', ['model' => __('models/tenants.plural')])
        );
    }
    /**
     * Register a new user
     *
     * @param RegisterUserAPIRequest $request
     * @return Model
     */
    public function create(RegisterTenantAPIRequest $request)
    {
        $input = $request->all();

        $user = TenantService::save($input);

        return $this->sendResponse(
            new TenantResource($user),
            __('messages.saved', ['model' => __('models/tenants.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param RegisterUserAPIRequest $request
     * @return Model
     */
    public function show($id, ShowTenantAPIRequest $request)
    {
        $input = $request->all();
        $user = TenantService::find($id);

        return $this->sendResponse(
            new TenantResource($user),
            __('messages.retrieved', ['model' => __('models/tenants.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param Request $request
     * @return Model
     */
    public function update($id, UpdateTenantAPIRequest $request)
    {

        $input = $request->all();


        $user = TenantService::update($id, $input);

        return $this->sendResponse(
            new TenantResource($user),
            __('messages.saved', ['model' => __('models/tenants.singular')])
        );
    }




    /**
     * Update a new user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, DeleteTenantAPIRequest $request)
    {
        $qtdDel = TenantService::delete($id);

        return $this->sendSuccess(
            __('messages.deleted', ['model' => __('models/tenants.singular')])
        );
    }
}
