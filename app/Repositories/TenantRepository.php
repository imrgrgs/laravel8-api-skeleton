<?php

namespace App\Repositories;

use App\Models\Tenant;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class UserRepository
 * @package App\Repositories
 */

class TenantRepository extends BaseRepository
{
    public function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('is_active'),

            'name',
            'users.name',

        ];
    }

    public function getAllowedIncludes()
    {
        return [
            'users',
        ];
    }

    public function getAllowedFields()
    {
        return [
            'id',
            'name',
            'users.name', 'users.id', 'users.tenant_id',

        ];
    }

    public function getAllowedSorts()
    {
        return [
            'name',

        ];
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Tenant::class;
    }
}
