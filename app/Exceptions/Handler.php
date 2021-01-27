<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        return $response;
    }

    public function handleException($request, Throwable $exception)
    {

        $code = $exception->getCode();
        $message = $exception->getMessage();
        $data = [];
        // if ($exception instanceof ValidationException) {
        //     $validator = $exception->validator;
        //     $data = $validator->errors();
        //     $code = \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY;
        // }


        if ($exception instanceof AuthenticationException) {
            $code = \Illuminate\Http\Response::HTTP_UNAUTHORIZED;
        }

        // token jwt
        if ($exception instanceof TokenExpiredException) {
            $code = \Illuminate\Http\Response::HTTP_UNAUTHORIZED;
            $message = 'Token expired';
        }

        if ($exception instanceof TokenInvalidException) {
            $code = \Illuminate\Http\Response::HTTP_UNAUTHORIZED;
            $message = 'Token expired';
        }
        if ($exception instanceof ModelNotFoundException) {
            $code = \Illuminate\Http\Response::HTTP_NOT_FOUND;

            $model = __('models/' . strtolower(str_replace('App\\Models\\', '', Str::plural($exception->getModel()))) . '.singular');
            $message = __('messages.not_found', ['model' => ucwords($model)]);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $code = \Illuminate\Http\Response::HTTP_METHOD_NOT_ALLOWED;
        }


        if ($exception instanceof NotFoundHttpException) {
            $code = \Illuminate\Http\Response::HTTP_NOT_FOUND;
            $message = 'The specified URL cannot be found';
        }

        if ($exception instanceof HttpException) {
            $code = \Illuminate\Http\Response::HTTP_NOT_FOUND;
        }

        if ($code < 100 || $code >= 600) {
            $code = \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($code > 499) {
            Log::critical($exception);
            $message = __('messages.http_internal_server_error');
        }

        if (config('app.debug')) {
            Log::info($exception);
        }


        if ($request->expectsJson() or $request->isXmlHttpRequest()) {
            return $this->sendError($message, $code, $data);
        }
        return $this->sendError($message, $code, $data);
        // return parent::render($request, $exception);
    }
}
