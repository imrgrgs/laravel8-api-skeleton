<?php

namespace App\Repositories;

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

class RoleRepository extends BaseRepository
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
        return Role::class;
    }

    public function getRoleLevel($name)
    {
        Log::debug("role name " . $name);
        $oRole = $this->getByColumnOrFail('name', $name);
        $rolesLevel = Config::get('permissions.roles');
        if (!$rolesLevel) {
            $message = 'The configuration config/permissions.php has not been found. Did you have config/permissions.php file';
            throw new Exception($message, Response::HTTP_NOT_FOUND);
        }
        $level = '';
        foreach ($rolesLevel as  $role) {
            if ($role['name'] == $name) {
                $level =  $role['level'];
            }
        }

        if (!$level) {
            $message = 'The configuration config/permissions.php has not role ' . $name . ' in array roles';
            throw new Exception($message, Response::HTTP_NOT_FOUND);
        }

        return $level;
    }

    public function permissions($name)
    {
        return $this->getByColumn('name', $name)->permissions;
    }
}
