<?php

namespace App\Http\Controllers\API\User;


use App\Facades\UserService;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\ListUserAPIRequest;
use App\Http\Requests\API\ShowUserAPIRequest;
use App\Http\Resources\UserResourceCollection;
use App\Http\Requests\API\DeleteUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Http\Requests\API\RegisterUserAPIRequest;


class UserAPIController extends APIController
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
    public function index(ListUserAPIRequest $request)
    {
        $users = UserService::query(
            $request->get('skip'),
            $request->get('limit')
        );


        return $this->sendResponse(
            new UserResourceCollection($users),
            __('messages.retrieved', ['model' => __('models/users.plural')])
        );
    }
    /**
     * Register a new user
     *
     * @param RegisterUserAPIRequest $request
     * @return Model
     */
    public function create(RegisterUserAPIRequest $request)
    {
        $input = $request->except(['avatar', 'roles']);
        $avatar = $request->file('avatar');
        $rolesToAttach = $request->get('roles');
        $user = UserService::save($input, $rolesToAttach, $avatar);

        return $this->sendResponse(
            new UserResource($user),
            __('messages.saved', ['model' => __('models/users.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param ShowUserAPIRequest $request
     * @return Model
     */
    public function show($id, ShowUserAPIRequest $request)
    {
        $input = $request->all();
        $user = UserService::find($id);

        return $this->sendResponse(
            new UserResource($user),
            __('messages.retrieved', ['model' => __('models/users.singular')])
        );
    }

    /**
     * Update a new user
     *
     * @param UpdateUserAPIRequest $request
     * @return Model
     */
    public function update($id, UpdateUserAPIRequest $request)
    {

        $input = $request->except('avatar');
        $avatar = $request->file('avatar');


        $user = UserService::update($id, $input, $avatar);

        return $this->sendResponse(
            new UserResource($user),
            __('messages.saved', ['model' => __('models/users.singular')])
        );
    }


    /**
     * Deactive an User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeActiveStatus($id, UpdateUserAPIRequest $request)
    {
        $active = UserService::isActive($id);
        if ($active) {
            return $this->deactive($id, $request);
        } else {
            return $this->active($id, $request);
        }
    }
    /**
     * Deactive an User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactive($id, UpdateUserAPIRequest $request)
    {
        $user = UserService::deactive($id);
        return $this->sendSuccess(
            __('auth.deactive_success')
        );
    }

    /**
     * Deactive an User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function active($id, UpdateUserAPIRequest $request)
    {
        $user = UserService::active($id);
        return $this->sendSuccess(
            __('auth.active_success')
        );
    }

    /**
     * Deactive an User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAvatar($id, UpdateUserAPIRequest $request)
    {
        $user = UserService::deleteAvatar($id);
        return $this->sendSuccess(
            __('auth.avatar_success')
        );
    }

    /**
     * Deactive an User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeAvatar($id, UpdateUserAPIRequest $request)
    {
        $avatar = $request->file('avatar');
        $user = UserService::changeAvatar($id, $avatar);
        return $this->sendSuccess(
            __('auth.avatar_success')
        );
    }


    /**
     * Update a new user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, DeleteUserAPIRequest $request)
    {
        $qtdDel = UserService::delete($id);

        return $this->sendSuccess(
            __('messages.deleted', ['model' => __('models/users.singular')])
        );
    }
}
