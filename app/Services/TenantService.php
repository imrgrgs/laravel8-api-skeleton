<?php

namespace App\Services;




use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use App\Services\Generator\HashCode;
use App\Repositories\TenantRepository;
use Illuminate\Database\Eloquent\Collection;


class TenantService
{
    /** @var  TenantRepository */
    private $tenantRepository;

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
        $this->tenantRepository = new TenantRepository();
    }



    /**
     * Save a new instance
     *
     * @param Array $input
     * @return app/Models/Param a new param saved
     */
    public function save(array $input)
    {

        DB::beginTransaction();
        $tenant = $this->tenantRepository->create([
            'name' => ucwords($input['name']),
            'code' => HashCode::make(),
        ]);

        DB::commit();
        return $tenant;
    }

    /**
     * Obtains an especific instance
     *
     * @param int $id unique auto increment id
     * @return Model
     */
    public function find($id, $columns = ['*'])
    {
        return $this->tenantRepository->find($id, $columns);
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
        $tenant = $this->tenantRepository->update($input, $id);
        DB::commit();


        return $tenant;
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
        $qtdDel = $this->tenantRepository->delete($id);
        DB::commit();
        return $qtdDel;
    }


    public function query($skip, $limit)
    {
        $tenants = $this->tenantRepository->allOrFail(
            $skip,
            $limit
        );
        return $tenants;
    }




    /**
     * get Tenant by name
     *
     * @param string $name a Tenant name
     * @return Tenant
     */
    public function getByName(string $name): Tenant
    {
        return $this->tenantRepository->getByColumn('name', $name);
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
        return $this->tenantRepository->firstOrCreate($key, $input);
    }

    /**
     * Gets a Tenant by id
     *
     * @param Bigint $id
     * @return Tenant an Tenant object
     */
    public  function getById(int $id): Tenant
    {
        return $this->tenantRepository->getByColumn('id', $id);
    }

    /**
     * returns a users object colletion
     *
     * @param Tenant $object a Tenant object
     * @return Collection containning users objects|null
     */
    public static function getUsers(Tenant $object): Collection
    {
        return $object->users;
    }

    /**
     * Turns 'true' active user
     *
     * @param int $id unique auto increment id
     * @return Model user activeted
     */
    public function active($id)
    {
        return $this->update($id, ['is_active' => true]);
    }

    /**
     * Turns 'false' active user
     *
     * @param int $id unique auto increment id
     * @return Model user deactiveted
     */
    public function deactive($id)
    {
        return $this->update($id, ['is_active' => false]);
    }
}
