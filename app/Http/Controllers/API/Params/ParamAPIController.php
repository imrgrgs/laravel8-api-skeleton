<?php

namespace App\Http\Controllers\API\Params;


use Illuminate\Http\Request;
use App\Services\ParamService;
use App\Http\Resources\ParamResource;
use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\ListParamAPIRequest;
use App\Http\Resources\ParamResourceCollection;
use App\Http\Requests\API\UpdateParamAPIRequest;
use App\Http\Requests\API\RegisterUserAPIRequest;
use App\Http\Requests\API\RegisterParamAPIRequest;



class ParamAPIController extends APIController
{
    /**
     * Undocumented variable
     *
     * @var ParamService
     */
    private $paramService;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->setServices();
    }

    private function setServices()
    {
        $this->paramService = new ParamService();
    }
    /**
     * List all .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListParamAPIRequest $request)
    {
        $params = $this->paramService->query(
            $request->get('skip'),
            $request->get('limit')
        );


        return $this->sendResponse(
            new ParamResourceCollection($params),
            __('messages.retrieved', ['model' => __('models/params.plural')])
        );
    }
    /**
     * Register a new
     *
     * @param RegisterParamAPIRequest $request
     * @return Model
     */
    public function create(RegisterParamAPIRequest $request)
    {
        $input = $request->except(['display_names', 'descriptions']);

        $displayNames = $request->get('display_names');
        $descriptions = $request->get('descriptions');
        $param = $this->paramService->save($input, $displayNames, $descriptions);

        return $this->sendResponse(
            new ParamResource($param),
            __('messages.saved', ['model' => __('models/params.singular')])
        );
    }

    /**
     * show
     *
     * @param RegisterUserAPIRequest $request
     * @return Model
     */
    public function show($id, Request $request)
    {
        $input = $request->all();
        $param = $this->paramService->find($id);

        return $this->sendResponse(
            new ParamResource($param),
            __('messages.retrieved', ['model' => __('models/params.singular')])
        );
    }

    /**
     * Update
     *
     * @param RegisterUserAPIRequest $request
     * @return Model
     */
    public function update($id, UpdateParamAPIRequest $request)
    {

        $input = $request->except(['display_names', 'descriptions']);

        $displayNames = $request->get('display_names');
        $descriptions = $request->get('descriptions');


        $param = $this->paramService->update($id, $input, $displayNames, $descriptions);

        return $this->sendResponse(
            new ParamResource($param),
            __('messages.saved', ['model' => __('models/users.singular')])
        );
    }



    /**
     * Update a new user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $qtdDel = $this->paramService->delete($id);

        return $this->sendSuccess(
            __('messages.deleted', ['model' => __('models/users.singular')])
        );
    }
}
