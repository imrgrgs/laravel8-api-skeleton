<?php

namespace App\Http\Controllers\API\Tenant;


use Illuminate\Http\Request;



use App\Facades\TenantService;
use App\Http\Resources\TenantResource;
use App\Http\Requests\API\TenantRequest;
use App\Http\Controllers\API\APIController;

use App\Http\Resources\TenantResourceCollection;




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
    public function index(TenantRequest $request)
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
     * @param TenantRequest $request
     * @return Model
     */
    public function create(TenantRequest $request)
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
     * @param TenantRequest $request
     * @return Model
     */
    public function show($id, TenantRequest $request)
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
    public function update($id, TenantRequest $request)
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
    public function destroy($id, TenantRequest $request)
    {
        $qtdDel = TenantService::delete($id);

        return $this->sendSuccess(
            __('messages.deleted', ['model' => __('models/tenants.singular')])
        );
    }

    /**
     * Deactive an Tenant.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactive($id, TenantRequest $request)
    {
        $user = TenantService::deactive($id);
        return $this->sendSuccess(
            __('auth.deactive_success')
        );
    }

    /**
     * Active an Tenant.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active($id, TenantRequest $request)
    {
        $user = TenantService::active($id);
        return $this->sendSuccess(
            __('auth.active_success')
        );
    }
}
