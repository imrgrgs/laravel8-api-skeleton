<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;

trait ApiCheckPermission
{

    protected function hasPermission($action)
    {
        if (!auth()->user()->isAbleTo($action)) {
            $message = __('messages.no_permission');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
    }
}
