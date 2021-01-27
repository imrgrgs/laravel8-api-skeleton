<?php

namespace App\Repositories;


use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\App;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var Application
     */
    //  protected $app;

    /**
     * @param Application $app
     *
     * @throws \Exception
     */
    // public function __construct(Application $app)
    public function __construct()
    {
        //   $this->app = $app;
        $this->makeModel();
        $this->newQuery();
    }

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

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel()
    {
        // $model = $this->app->make($this->model());
        $model = App::make($this->model());
        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model", 500);
        }

        return $this->model = $model;
    }




    public function allQuery()
    {
        return $this->query
            ->allowedFilters($this->getAllowedFilters())
            ->allowedFields($this->getAllowedFields())
            ->allowedIncludes($this->getAllowedIncludes())
            ->allowedSorts($this->getAllowedSorts());
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

    /**
     * Create model record
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
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

        return $this->query->find($id, $columns);
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

        return $this->query->findOrFail($id, $columns);
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

        return $this->query->where($column, $value)->first($columns);
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
    public function getByColumnOrFail($column, $value, array $columns = ['*'])
    {

        return $this->query->where($column, $value)->firstOrFail($columns);
    }


    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update($input, $id)
    {


        $model =  $this->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @param int $id
     *
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete($id)
    {

        $model = $this->findOrFail($id);

        return $model->delete();
    }


    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery()
    {
        $this->query = QueryBuilder::for($this->model());

        return $this;
    }
}
