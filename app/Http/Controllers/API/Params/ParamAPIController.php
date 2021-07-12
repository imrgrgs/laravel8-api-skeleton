<?php

namespace App\Http\Controllers\API\Params;


use Illuminate\Http\Request;
//use App\Services\ParamService;
use App\Facades\ParamService;
use App\Http\Resources\ParamResource;
use App\Http\Requests\API\ParamRequest;
use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\ListParamAPIRequest;
use App\Http\Requests\API\ShowParamAPIRequest;
use App\Http\Resources\ParamResourceCollection;
use App\Http\Requests\API\DeleteParamAPIRequest;
use App\Http\Requests\API\UpdateParamAPIRequest;
use App\Http\Resources\ParamDescriptionResource;
use App\Http\Requests\API\RegisterUserAPIRequest;
use App\Http\Requests\API\RegisterParamAPIRequest;
use App\Http\Resources\ParamValueResourceCollection;

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
    public function index(ParamRequest $request)
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
     * @param ParamRequest $request
     * @return Model
     */
    public function create(ParamRequest $request)
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
     * @param ParamRequest $request
     * @return Model
     */
    public function show($id, ParamRequest $request)
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
     * @param ParamRequest $request
     * @return Model
     */
    public function update($id, ParamRequest $request)
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
    public function destroy($id, ParamRequest $request)
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
        $paramDescription = ParamService::getDescription($id);

        return $this->sendResponse(
            new ParamDescriptionResource($paramDescription),
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
        $paramValues = ParamService::getValuesParam($id);

        return $this->sendResponse(
            new ParamValueResourceCollection($paramValues),
            __('messages.retrieved', ['model' => __('models/params.singular')])
        );
    }
}
