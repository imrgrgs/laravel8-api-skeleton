<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{

    protected function sendSuccess($message, $code = JsonResponse::HTTP_OK)
    {
        return response()->json([
            'status' => $code,
            'success' => true,
            'message' => $message,
        ], $code);
    }

    protected function sendResponse($data, $message = null, $code = JsonResponse::HTTP_OK)
    {
        return response()->json([
            'status' => $code,
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function sendNoContent($message = null, $code = JsonResponse::HTTP_NO_CONTENT)
    {
        return response()->json([
            'status' => $code,
            'success' => true,
            'message' => $message,
        ], $code);
    }

    protected function sendError($message = null, $code = JsonResponse::HTTP_NOT_FOUND, $data = [])
    {
        if ($code == JsonResponse::HTTP_UNPROCESSABLE_ENTITY) {
            return $this->sendErrorUnprocessable($data, $message);
        }
        return response()->json([
            'status' => $code,
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }

    protected function sendErrorUnprocessable($errors = [], $message = null, $code = JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'status' => $code,
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
