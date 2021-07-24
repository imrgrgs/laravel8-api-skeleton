<?php

namespace App\Http\Controllers\API\Installs;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\API\APIController;
use App\Traits\Images;

class UserAvatarAPIController extends APIController
{
    use Images;

    public function __construct()
    {
    }
    /**
     * Generate an user avatar default.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeAvatar(Request $request)
    {
        $avatar = $request->file('avatar');

        $storage = User::AVATAR_STORAGE;
        $delAvatar = User::AVATAR_DEFAULT;
        if ($delAvatar) {
            if (!$this->deleteImage($delAvatar, $storage)) {
                Log::info('Não excluiu a imagem ' . $delAvatar);
            };
        }
        if ($avatar) {
            $options['nameToSave'] =  'default-avatar';
            $names = $this->loadImage($avatar, $storage, $options);
            $avatar = $names['nameSaved'];
        }



        return $this->sendSuccess(
            __('auth.avatar_success')
        );
    }
}
