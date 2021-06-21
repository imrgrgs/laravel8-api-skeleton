<?php

namespace App\Repositories;

use App\Models\Param;
use App\Repositories\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;

class ParamRepository extends BaseRepository
{
    public function getAllowedFilters()
    {
        return [
            'name',
            'display_name',
            'description.description',
            AllowedFilter::exact('id'),
            AllowedFilter::exact('values.code')

        ];
    }

    public function getAllowedIncludes()
    {
        return [
            'values',
            'description'
        ];
    }

    public function getAllowedFields()
    {
        return [
            'id',
            'name',
            'display_name',
            'description.description', 'decription.id',
            'values.id', 'values.name', 'values.code',
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
        return Param::class;
    }
}
