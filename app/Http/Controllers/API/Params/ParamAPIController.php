<?php

namespace App\Http\Controllers\API\Params;


use Illuminate\Http\Request;
//use App\Services\ParamService;
use App\Facades\ParamService;
use App\Http\Resources\ParamResource;
use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\DeleteParamAPIRequest;
use App\Http\Requests\API\ListParamAPIRequest;
use App\Http\Resources\ParamResourceCollection;
use App\Http\Requests\API\UpdateParamAPIRequest;
use App\Http\Requests\API\RegisterUserAPIRequest;
use App\Http\Requests\API\RegisterParamAPIRequest;
use App\Http\Requests\API\ShowParamAPIRequest;

class ParamAPIController extends APIController
{
    /**
     * Undocumented variable
     *
     * @var ParamService
     */
    //   private $paramService;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    /**
     * List all .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ListParamAPIRequest $request)
    {
        $params = ParamService::query(
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
        $input = $request->except(['display_names', 'descriptions', 'values']);

        $displayNames = $request->get('display_names');
        $descriptions = $request->get('descriptions');
        $values = $request->get('values');
        $param = ParamService::save($input, $displayNames, $descriptions, $values);

        return $this->sendResponse(
            new ParamResource($param),
            __('messages.saved', ['model' => __('models/params.singular')])
        );
    }

    /**
     * show
     *
     * @param ShowParamAPIRequest $request
     * @return Model
     */
    public function show($id, ShowParamAPIRequest $request)
    {
        $input = $request->all();
        $param = ParamService::find($id);

        return $this->sendResponse(
            new ParamResource($param),
            __('messages.retrieved', ['model' => __('models/params.singular')])
        );
    }

    /**
     * Update
     *
     * @param UpdateParamAPIRequest $request
     * @return Model
     */
    public function update($id, UpdateParamAPIRequest $request)
    {

        $input = $request->except(['display_names', 'descriptions']);

        $displayNames = $request->get('display_names');
        $descriptions = $request->get('descriptions');


        $param = ParamService::update($id, $input, $displayNames, $descriptions);

        return $this->sendResponse(
            new ParamResource($param),
            __('messages.saved', ['model' => __('models/params.singular')])
        );
    }



    /**
     * Update a new user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, DeleteParamAPIRequest $request)
    {
        $qtdDel = ParamService::delete($id);

        return $this->sendSuccess(
            __('messages.deleted', ['model' => __('models/params.singular')])
        );
    }

    /**
     * get a param description
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function description($id)
    {
        $description = ParamService::getDescription($id);

        return $this->sendResponse(
            new ParamResource($description),
            __('messages.retrieved', ['model' => __('models/params.singular')])
        );
    }

    /**
     * get a param description
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function listValues($id)
    {
        $description = ParamService::getValuesParam($id);

        return $this->sendResponse(
            new ParamResource($description),
            __('messages.retrieved', ['model' => __('models/params.singular')])
        );
    }
}
