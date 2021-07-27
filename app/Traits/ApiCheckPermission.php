<?php

namespace App\Traits;

use App\Exceptions\NotHasRoleException;
use App\Facades\TenantService;
use Exception;
use Illuminate\Http\JsonResponse;

trait ApiCheckPermission
{
    protected function hasRole($roles)
    {
        if (!auth()->user()->hasRole($roles)) {
            $message = __('auth.no_permission');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
    }

    protected function hasPermission($permissions)
    {
        if (!auth()->user()->isAbleTo($permissions)) {
            $message = __('auth.no_permission');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
    }

    protected function isActive()
    {
        $login = auth()->user();

        if (!$login->active) {
            $message = __('auth.user_not_active');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
        if (!$login->tenant_id) {
            $message = __('auth.user_not_tenant');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
        $tenant = $login->tenant;
        if (!$tenant) {
            $message = __('auth.user_not_tenant');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
        if (!$tenant->is_active) {
            $message = __('auth.tenant_not_active');
            throw new Exception($message, JsonResponse::HTTP_FORBIDDEN);
        }
    }
}
