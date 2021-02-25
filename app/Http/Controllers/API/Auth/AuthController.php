<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\API\APIController;
use App\Http\Requests\API\ListUserAPIRequest;
use App\Http\Requests\API\LoginUserAPIRequest;
use App\Http\Resources\UserResourceCollection;
use App\Http\Requests\API\RegisterUserAPIRequest;

class AuthController extends APIController
{
    /**
     * Undocumented variable
     *
     * @var UserService
     */
    private $userService;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->setServices();
    }


    /**
     * Register a new user
     *
     * @param RegisterUserAPIRequest $request
     * @return Model
     */

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserAPIRequest $request)
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return $this->sendError(
                __('auth.failed')
            );
        }
        $user =  auth()->user();
        if (!$user->active) {
            return $this->sendError(
                __('auth.not_active')
            );
        }
        return $this->respondWithToken($token);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user =  $this->userService->profile();
        if (!$user) {
            return $this->sendError(
                __('auth.unauthorized')
            );
        }
        return $this->sendResponse(
            new UserResource($user),
            __('messages.retrieved', ['model' => __('models/users.singular')])
        );
    }



    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->sendSuccess(__('auth.successfully_logged_out'));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->sendResponse(
            new UserResource([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => auth()->user(),
            ]),
            __('messages.retrieved', ['model' => __('models/users.singular')])
        );
    }

    /**
     * Starts and creates services class
     *
     * @return void
     */
    private function setServices()
    {
        $this->userService = new UserService();
    }
}
