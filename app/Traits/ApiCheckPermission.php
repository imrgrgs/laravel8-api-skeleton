<?php

namespace App\Traits;

use App\Exceptions\NotHasRoleException;
use Exception;
use Illuminate\Http\JsonResponse;

trait ApiCheckPermission
{
    protected function hasRole($roles)
    {
        if (!auth()->user()->hasRole($roles)) {
            $message = __('messages.no_permission');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
    }

    protected function hasPermission($permissions)
    {
        if (!auth()->user()->isAbleTo($permissions)) {
            $message = __('messages.no_permission');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
    }
}
