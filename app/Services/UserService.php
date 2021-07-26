<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;

use App\Models\User;
use App\Traits\Images;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;

class UserService
{


    use Images;

    /** @var  UserRepository */
    private $userRepository;

    /** @var  RoleRepository */
    private $roleRepository;

    /**
     * User object logged on
     *
     * @var User
     */
    private $login;

    public function __construct()
    {
        $this->setRepositories();
        $this->login = auth()->user();
    }
    /**
     * Initialize repositories
     *
     * @return void
     */
    private function setRepositories()
    {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository();
    }

    /**
     * Save a new User
     *
     * @param Array $input
     * @return app/Models/User a new user saved
     */
    public function save(array $input, array $roles = null, $permissions = null, $avatar = null)
    {
        $rolesToAtach = [];
        $permissionsToAtach = [];


        if ($roles) {
            $maxLoginRoleLevel = 0;
            $loginRoles = $this->login->roles;
            $forbidenRoles = [];

            foreach ($loginRoles as $role) {
                $level = $this->roleRepository->getRoleLevel($role->name);
                if ($level > $maxLoginRoleLevel) {
                    $maxLoginRoleLevel = $level;
                }
            }


            foreach ($roles as $role) {
                if ($this->roleRepository->getRoleLevel($role['name']) > $maxLoginRoleLevel) {
                    $forbidenRoles[] = $role['name'];
                } else {
                    $rolesToAtach[] = $this->roleRepository->getByColumnOrFail(
                        'name',
                        $role['name']
                    );
                }
            }

            if ($forbidenRoles) {
                $message = __('messages.cant_register_user_with_role_greater', ['not_allowed' => implode(', ', $forbidenRoles)]);
                throw new Exception($message, \Illuminate\Http\Response::HTTP_FORBIDDEN);
            }
        }


        if ($avatar) {
            $storage = User::AVATAR_STORAGE;
            $names = $this->saveImage($avatar, $storage);
            $avatar = $names['nameSaved'];
        }

        DB::beginTransaction();
        $user = $this->userRepository->create([
            'tenant_id' => $this->login->tenant_id,
            'name' => ucwords($input['name']),
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
            'avatar' => $avatar,
        ]);

        if ($rolesToAtach) {
            foreach ($rolesToAtach as $role) {
                $user->attachRole($role);
            }
        }

        DB::commit();
        return $user;
    }

    /**
     * Obtains an especific user
     *
     * @param int $id unique auto increment id
     * @return Model User
     */
    public function find($id, $columns = ['*'])
    {
        return $this->userRepository->find($id, $columns);
    }

    /**
     * Update an user
     *
     * @param int $id unique auto increment id
     * @param Array $input
     * @return Model user updated
     */
    public function update($id, $input, $avatar = null)
    {
        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }
        DB::beginTransaction();
        $user = $this->userRepository->update($input, $id);
        DB::commit();

        $storage = User::AVATAR_STORAGE;
        $names = [];
        if ($avatar) {
            $delAvatar = $user->avatar;
            if ($delAvatar) {
                if (!$this->deleteImage($delAvatar, $storage)) {
                    Log::info('Não excluiu a imagem ' .  $delAvatar);
                };
            }
            $names = $this->saveImage($avatar, $storage);
            $avatar = $names['nameSaved'];
            DB::beginTransaction();
            $user->update(['avatar' => $avatar]);
            DB::commit();
        }
        return $user;
    }

    public function deleteAvatar($id)
    {
        $user = $this->userRepository->findOrFail($id);
        $delAvatar = $user->avatar;
        $storage = User::AVATAR_STORAGE;
        if ($delAvatar) {
            if (!$this->deleteImage($delAvatar, $storage)) {
                Log::info('Não excluiu a imagem ' . $delAvatar);
            };
            DB::beginTransaction();
            $user = $this->userRepository->update(['avatar' => null], $id);
            DB::commit();
        }


        return $user;
    }

    public function changeAvatar($id, $avatar = null)
    {
        $user = $this->userRepository->findOrFail($id);
        $delAvatar = $user->avatar;
        $storage = User::AVATAR_STORAGE;

        if ($delAvatar) {
            if (!$this->deleteImage($delAvatar, $storage)) {
                Log::info('Não excluiu a imagem ' . $delAvatar);
            };
        }
        if ($avatar) {

            $names = $this->saveImage($avatar, $storage);
            $avatar = $names['nameSaved'];
        }


        DB::beginTransaction();
        $user = $this->userRepository->update(['avatar' => $avatar], $id);
        DB::commit();

        return $user;
    }


    /**
     * Delete an especific user from database
     *
     * @param int $id unique auto increment id
     * @return int number of deleted rows
     */
    public function delete($id)
    {
        $user = $this->userRepository->findOrFail($id, ['avatar']);
        $delAvatar = $user->avatar;
        $storage = User::AVATAR_STORAGE;
        if ($delAvatar) {
            if (!$this->deleteImage($delAvatar, $storage)) {
                Log::info('Não excluiu a imagem ' . $delAvatar);
            };
        }

        DB::beginTransaction();
        $qtdDel = $this->userRepository->delete($id);
        DB::commit();
        return $qtdDel;
    }


    public function query($skip, $limit)
    {
        $users = $this->userRepository->allOrFail(
            $skip,
            $limit
        );
        return $users;
    }
    /**
     * Turns 'true' active user
     *
     * @param int $id unique auto increment id
     * @return Model user activeted
     */
    public function isActive($id)
    {
        $user = $this->find($id);
        if ($user->active) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Turns 'true' active user
     *
     * @param int $id unique auto increment id
     * @return Model user activeted
     */
    public function changeActiveStatus($id)
    {
        $userLoged =  auth()->user();
        if ($userLoged->id == $id) {
            $message = __('auth.active_same');
            throw new Exception($message, \Illuminate\Http\Response::HTTP_FORBIDDEN);
        }

        if ($this->isActive($id)) {
            return $this->deactive($id);
        } else {
            return $this->active($id);
        }
    }

    /**
     * Turns 'true' active user
     *
     * @param int $id unique auto increment id
     * @return Model user activeted
     */
    public function active($id)
    {
        $userLoged =  auth()->user();
        if ($userLoged->id == $id) {
            $message = __('auth.active_same');
            throw new Exception($message, \Illuminate\Http\Response::HTTP_FORBIDDEN);
        }
        return $this->update($id, ['active' => true]);
    }

    /**
     * Turns 'false' active user
     *
     * @param int $id unique auto increment id
     * @return Model user deactiveted
     */
    public function deactive($id)
    {
        $userLoged =  auth()->user();
        if ($userLoged->id == $id) {
            $message = __('auth.active_same');
            throw new Exception($message, \Illuminate\Http\Response::HTTP_FORBIDDEN);
        }
        return $this->update($id, ['active' => false]);
    }

    public function setAvatar($avatar, $id)
    {
    }


    /**
     * Returns user logged data
     *
     * @return Model user
     */
    public function profile()
    {
        $user =  auth()->user();
        $profile = $this->userRepository->find($user->id);
        $roles = $this->roles($user->id);

        //        $profile->roles = $roles;

        // $profile->allPermissions = $this->allPermissions($user->id);
        // return $profile;
        $allPermissions = $this->allPermissions($user->id);
        return ['user' => $profile, 'roles' => $roles, 'allPermissions' => $allPermissions];
    }

    public function roles($id)
    {
        return $this->userRepository->roles($id);
    }

    public function allPermissions($id)
    {
        return $this->userRepository->allPermissions($id);
    }
}
