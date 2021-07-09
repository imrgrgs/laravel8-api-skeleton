<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->json([
        'status' => 200,
        'success' => true,
        'message' => config('app.name') . ' vs. ' . config('app.version'),
        'data' => ['Developer' => config('app.developer'), 'Copyright' => '2020 - ' . Carbon::now()->format('Y')]
    ], 200);
});


/**
 * Default group routes access
 */
$installs = [
    'prefix' => 'installs',
    'domain' => '',
    'middleware' => 'localization',
    'as' => 'installs.',
    'namespace' => 'Installs',
];
Route::group($installs, function () {
    Route::post('change-avatar', ['as' => 'change.avatar', 'uses' => 'UserAvatarAPIController@changeAvatar',]);
}); // end group installs

/**
 * Auth group routes access
 */
$auth = [
    'prefix' => 'auth',
    'domain' => '',
    'middleware' => 'localization',
    'as' => 'auth.',
    'namespace' => 'Auth',
];
Route::group($auth, function () {
    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@login',]);
    Route::post('logout', ['as' => 'logout', 'uses' => 'AuthController@logout',]);
    Route::post('refresh', ['as' => 'refresh', 'uses' => 'AuthController@refresh',]);
    Route::post('me', ['as' => 'me', 'uses' => 'AuthController@me',]);
}); // end group auth


/**
 * Params group routes access
 */
$params = [
    'prefix' => 'params',
    'domain' => '',
    'middleware' => ['jwt.auth', 'localization'],
    'as' => 'params.',
    'namespace' => 'Params',
];
Route::group($params, function () {
    Route::get('', ['as' => 'index', 'uses' => 'ParamAPIController@index',]);
    Route::post('', ['as' => 'create', 'uses' => 'ParamAPIController@create',]);
    Route::get('{param_id}', ['as' => 'show', 'uses' => 'ParamAPIController@show',]);
    Route::put('{param_id}', ['as' => 'update', 'uses' => 'ParamAPIController@update',]);
    Route::delete('{param_id}', ['as' => 'delete', 'uses' => 'ParamAPIController@destroy',]);
    Route::get('{param_id}/description', ['as' => 'description', 'uses' => 'ParamAPIController@description',]);

    Route::get('{param_id}/values', ['as' => 'list.values', 'uses' => 'ParamAPIController@listValues',]);
}); // end group params


/**
 * Tenants group routes
 */
$tenants = [
    'prefix' => 'tenants',
    'domain' => '',
    'middleware' => ['jwt.auth', 'localization'],
    'as' => 'tenants.',
    'namespace' => 'Tenant',
];
Route::group($tenants, function () {
    Route::get('', ['as' => 'index', 'uses' => 'TenantAPIController@index',]);
    Route::post('', ['as' => 'create', 'uses' => 'TenantAPIController@create',]);
    Route::get('{tenant_id}', ['as' => 'show', 'uses' => 'TenantAPIController@show',]);
    Route::put('{tenant_id}', ['as' => 'update', 'uses' => 'TenantAPIController@update',]);
    Route::delete('{tenant_id}', ['as' => 'delete', 'uses' => 'TenantAPIController@destroy',]);
    Route::put('{tenant_id}/active', ['as' => 'active', 'uses' => 'TenantAPIController@active',]);
    Route::put('{tenant_id}/deactive', ['as' => 'deactive', 'uses' => 'TenantAPIController@deactive',]);
}); // end group tenants

/**
 * User group routes access
 */
$user = [
    'prefix' => 'users',
    'domain' => '',
    'middleware' => ['jwt.auth', 'localization'],
    'as' => 'users.',
    'namespace' => 'User',
];
Route::group($user, function () {
    Route::get('', ['as' => 'index', 'uses' => 'UserAPIController@index',]);
    Route::post('', ['as' => 'create', 'uses' => 'UserAPIController@create',]);
    Route::get('{user_id}', ['as' => 'show', 'uses' => 'UserAPIController@show',]);
    Route::put('{user_id}', ['as' => 'update', 'uses' => 'UserAPIController@update',]);
    Route::delete('{user_id}', ['as' => 'delete', 'uses' => 'UserAPIController@destroy',]);
    Route::put('{user_id}/change-active-status', ['as' => 'change.active.status', 'uses' => 'UserAPIController@changeActiveStatus',]);
    Route::put('{user_id}/active', ['as' => 'active', 'uses' => 'UserAPIController@active',]);
    Route::put('{user_id}/deactive', ['as' => 'deactive', 'uses' => 'UserAPIController@deactive',]);
    Route::put('{user_id}/change-avatar', ['as' => 'change.avatar', 'uses' => 'UserAPIController@changeAvatar',]);
}); // end group user



Route::fallback(function () {
    return response()->json([
        'status' => 404,
        'success' => false,
        'message' => __('messages.page_not_found', ['contact' => config('app.contact_error')]), //Page Not Found. If error persists, contact ' . config('app.contact_error'),
        'data' => null
    ], 404);
});
