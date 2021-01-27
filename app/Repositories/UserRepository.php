<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class UserRepository
 * @package App\Repositories
 */

class UserRepository extends BaseRepository
{
    public function getAllowedFilters()
    {
        return [
            'name',
            'email',
            'module',
            AllowedFilter::exact('id'),
            AllowedFilter::exact('active'),

        ];
    }

    public function getAllowedIncludes()
    {
        return [
            'roles',
            'permissions'
        ];
    }

    public function getAllowedFields()
    {
        return [
            'id',
            'name',
            'email',
            'active',
            'avatar',
            'module',
        ];
    }

    public function getAllowedSorts()
    {
        return [
            'name',
            'email',
            'active',
            'module',
        ];
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function roles($id)
    {
        return $this->find($id)->roles;
    }

    public function allPermissions($id)
    {
        return $this->find($id)->allPermissions();
    }
}
