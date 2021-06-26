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

            'name',

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
