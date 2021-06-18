<?php

namespace App\Services\Role;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use phpDocumentor\Reflection\Types\Boolean;

class RoleQueryService
{

    /**
     * Retrieves an instance of Role
     *
     * @param string $column column name to be searched
     * @param mixed $value value of columns to be searched
     * @param array $columns an array containing a list of columns to be retrived [*](all columns)[default]
     * @param boolean $fail if true exception is returned if not found | if false null is returned when not found[default]
     * @return Role an role object | null if $fail=false| Exception if $fail=true
     */
    public function getByColumn(string $column, mixed $value, array $columns = ['*'], boolean $fail = false): Role
    {
        if ($fail) {
            return $this->query->where($column, $value)->firstOrFail($columns);
        } else {
            return $this->query->where($column, $value)->first($columns);
        }
    }


    /**
     * Returns a permission objects collection
     *
     * @param Role $role a role instance to get permissions list
     * @return Collection permission objects
     */
    public function permissions(Role $role): Collection
    {
        return $this->getByColumn('id', $role->id)->permissions;
    }
}
