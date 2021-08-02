<?php

namespace App\Services;




use App\Models\Role;
use App\Facades\PermissionService;
use Illuminate\Support\Facades\DB;
use App\Repositories\RoleRepository;
use App\Services\Generator\HashCode;
use Illuminate\Database\Eloquent\Collection;


class RoleService
{
    /** @var  RoleRepository */
    private $roleRepository;

    public function __construct()
    {
        $this->setRepositories();
    }
    /**
     * Initialize repositories
     *
     * @return void
     */
    private function setRepositories()
    {
        $this->roleRepository = new RoleRepository();
    }



    /**
     * Save a new instance
     *
     * @param Array $input
     * @return app/Models/Param a new param saved
     */
    public function save(array $input, array $displayNames = null, array $descriptions = null, array $permissions = null)
    {
        $displayName = $this->prepareParamDisplayName($displayNames);
        $description = $this->prepareParamDescription($descriptions);
        $permissionsId = [];
        if ($permissions) {
            foreach ($permissions as $permission) {

                $permissionsId[] = PermissionService::getByColumnOrFail(
                    'name',
                    $permission['name']
                )->id;
            }
        }



        DB::beginTransaction();
        $role = $this->roleRepository->create([
            'name' => $input['name'],
            'display_name' => $displayName,
            'description' => $description,
        ]);
        $role->permissions()->sync($permissionsId);
        DB::commit();
        return $role;
    }

    /**
     * Obtains an especific instance
     *
     * @param int $id unique auto increment id
     * @return Model
     */
    public function find($id, $columns = ['*'])
    {
        return $this->roleRepository->find($id, $columns);
    }

    /**
     * Update an param
     *
     * @param int $id unique auto increment id
     * @param Array $input
     * @return Model param updated
     */
    public function update(int $id, array $input)
    {
        DB::beginTransaction();
        $role = $this->roleRepository->update($input, $id);
        DB::commit();


        return $role;
    }

    /**
     * Delete an especific user from database
     *
     * @param int $id unique auto increment id
     * @return int number of deleted rows
     */
    public function delete($id)
    {

        DB::beginTransaction();
        $qtdDel = $this->roleRepository->delete($id);
        DB::commit();
        return $qtdDel;
    }


    public function query($skip, $limit)
    {
        $tenants = $this->roleRepository->allOrFail(
            $skip,
            $limit
        );
        return $tenants;
    }

    public function getRoleLevel(string $name): int
    {
        return $this->roleRepository->getRoleLevel($name);
    }

    public function getMaxRoleLevel($roles): int
    {
        $maxRoleLevel = 0;
        foreach ($roles as $role) {
            $level = $this->getRoleLevel($role->name);
            if ($level > $maxRoleLevel) {
                $maxRoleLevel = $level;
            }
        }
        return $maxRoleLevel;
    }


    /**
     * get Role by name
     *
     * @param string $name a Role name
     * @return Role
     */
    public function getByName(string $name): Role
    {
        return $this->roleRepository->getByColumn('name', $name);
    }


    public function getByColumnOrFail(string $column, $value): Role
    {
        return $this->roleRepository->getByColumnOrFail(
            $column,
            $value
        );
    }


    /**
     * Retry or Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function firstOrCreate(array $key, array $input)
    {
        return $this->roleRepository->firstOrCreate($key, $input);
    }

    /**
     * Gets a Role by id
     *
     * @param Bigint $id
     * @return Role an Role object
     */
    public  function getById(int $id): Role
    {
        return $this->roleRepository->getByColumn('id', $id);
    }

    /**
     * returns a users object colletion
     *
     * @param Role $object a Role object
     * @return Collection containning users objects|null
     */
    public static function getUsers(Role $object): Collection
    {
        return $object->users;
    }

    private function prepareParamDescription(array $descriptions = [])
    {
        $description = [];

        if ($descriptions) {
            foreach ($descriptions as $key => $value) {
                $description[$key] =  $value;
            }
        }
        return $description;
    }

    private function prepareParamDisplayName(array $displayNames = [])
    {
        $displayName = [];

        if ($displayNames) {
            foreach ($displayNames as $key => $value) {
                $displayName[$key] =  $value;
            }
        }
        return $displayName;
    }
}
