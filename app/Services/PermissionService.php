<?php

namespace App\Services;




use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Services\Generator\HashCode;
use App\Repositories\PermissionRepository;
use Illuminate\Database\Eloquent\Collection;


class PermissionService
{
    /** @var  PermissionRepository */
    private $permissionRepository;

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
        $this->permissionRepository = new PermissionRepository();
    }



    /**
     * Save a new instance
     *
     * @param Array $input
     * @return app/Models/Param a new param saved
     */
    public function save(array $input, array $displayNames = null, array $descriptions = null)
    {
        $displayName = $this->prepareParamDisplayName($displayNames);
        $description = $this->prepareParamDescription($descriptions);

        DB::beginTransaction();
        $permission = $this->permissionRepository->create([
            'name' => $input['name'],
            'display_name' => $displayName,
            'description' => $description,
        ]);

        DB::commit();
        return $permission;
    }

    /**
     * Obtains an especific instance
     *
     * @param int $id unique auto increment id
     * @return Model
     */
    public function find($id, $columns = ['*'])
    {
        return $this->permissionRepository->find($id, $columns);
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
        $permission = $this->permissionRepository->update($input, $id);
        DB::commit();


        return $permission;
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
        $qtdDel = $this->permissionRepository->delete($id);
        DB::commit();
        return $qtdDel;
    }


    public function query($skip, $limit)
    {
        $tenants = $this->permissionRepository->allOrFail(
            $skip,
            $limit
        );
        return $tenants;
    }




    /**
     * get Permission by name
     *
     * @param string $name a Permission name
     * @return Permission
     */
    public function getByName(string $name): Permission
    {
        return $this->permissionRepository->getByColumn('name', $name);
    }


    public function getByColumnOrFail(string $column, $value): Permission
    {
        return $this->permissionRepository->getByColumnOrFail(
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
        return $this->permissionRepository->firstOrCreate($key, $input);
    }

    /**
     * Gets a Permission by id
     *
     * @param Bigint $id
     * @return Permission an Permission object
     */
    public  function getById(int $id): Permission
    {
        return $this->permissionRepository->getByColumn('id', $id);
    }

    /**
     * returns a users object colletion
     *
     * @param Permission $object a Permission object
     * @return Collection containning users objects|null
     */
    public static function getUsers(Permission $object): Collection
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
