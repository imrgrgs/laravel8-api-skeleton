<?php

namespace App\Repositories;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;


abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    protected $query;

    /**
     * Get searchable fields array
     *
     * @return array
     */
    abstract public function getAllowedFilters();

    /**
     * Get relations includes array
     *
     * @return array
     */
    abstract public function getAllowedIncludes();
    /**
     * Get fields to show array
     *
     * @return array
     */
    abstract public function getAllowedFields();

    /**
     * Get fields to sort array
     *
     * @return array
     */
    abstract public function getAllowedSorts();



    /**
     * Configure the Model
     *
     * @return string
     */
    abstract public function model();

    public function __construct()
    {
        $this->model = $this->makeModel();
        $this->query = $this->newQuery();
    }

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel(): Model
    {

        $model = App::make($this->model());
        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model", 500);
        }

        return $model;
    }

    /**
     * Undocumented function
     *
     * @return QueryBuilder
     */
    protected function newQuery(): QueryBuilder
    {
        return QueryBuilder::for($this->model);
    }


    public function allQuery()
    {
        return $this->query()
            ->allowedFilters($this->getAllowedFilters())
            ->allowedFields($this->getAllowedFields())
            ->allowedIncludes($this->getAllowedIncludes())
            ->allowedSorts($this->getAllowedSorts());
    }
    protected function query(): QueryBuilder
    {
        return QueryBuilder::for($this->model);
    }
    /**
     * Retrieve all records with given filter criteria
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @param array $columns
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all($skip = null, $limit = null, $columns = ['*'])
    {

        return $this->allQuery()->get($columns);
    }

    public function allOrFail($skip = null, $limit = null, $columns = ['*'])
    {

        $objects=  $this->allQuery()->get($columns);
        if (count($objects)) {
            return $objects;
        }

        throw (new ModelNotFoundException)->setModel(get_class($this->model));
    }

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        $object = $this->model->newInstance($input);

        $object->save();

        return $object;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find($id, $columns = ['*'])
    {

        return $this->allQuery()->find($id, $columns);
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function findOrFail($id, $columns = ['*'])
    {

        return $this->query()->findOrFail($id, $columns);
    }

    /**
     * Obtains an especific row by gave column and value
     * @param string $column table field
     * @param string $value field value to search
     * @param array $columns tbale fields to show
     *
     * @return Model|null
     */
    public function getByColumn($column, $value, array $columns = ['*'])
    {

        return $this->query()->where($column, $value)->first($columns);
    }

    /**
     * Obtains an especific row by gave column and value
     * @param string $column table field
     * @param string $value field value to search
     * @param array $columns tbale fields to show
     *
     * @return Model
     * @throws ModelNotFoundException
     */
    public function getByColumnOrFail($column, $value, array $columns = ['*']): Model
    {
        return  $this->query()->where($column, $value)->firstOrFail($columns);
    }


    /**
     * Update data
     *
     * @param array $input
     * @param integer $id
     * @return Model
     */
    public function update(array $input, int $id): Model
    {


        $object =  $this->findOrFail($id);

        $object->fill($input);

        $object->save();

        return $object;
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function delete($id): bool
    {

        $object = $this->findOrFail($id);

        return $object->delete();
    }
}
