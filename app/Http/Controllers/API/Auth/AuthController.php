<?php

namespace App\Http\Controllers\API\Auth;

use App\Facades\UserService as FacadesUserService;



use App\Http\Resources\UserResource;

use App\Http\Controllers\API\APIController;

use App\Http\Requests\API\LoginRequest;



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
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }


    /**
     * Register a new user
     *
     * @param LoginRequest $request
     * @return Model
     */

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
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
        $user =  FacadesUserService::profile();
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
}
