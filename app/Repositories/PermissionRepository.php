<?php

namespace App\Repositories;

use App\Models\Permission;
use Exception;
use App\Models\Role;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Config;
use Spatie\QueryBuilder\AllowedFilter;


/**
 * Class UserRepository
 * @package App\Repositories
 */

class PermissionRepository extends BaseRepository
{
    public function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('name'),
            AllowedFilter::exact('id'),
        ];
    }

    public function getAllowedIncludes()
    {
        return [];
    }

    public function getAllowedFields()
    {
        return [
            'id',
            'name',
            'display_name',
            'description',
        ];
    }

    public function getAllowedSorts()
    {
        return [
            'id',
            'name',
            'display_name',
            'description',
        ];
    }



    /**
     * Configure the Model
     **/
    public function model()
    {
        return Permission::class;
    }
}
