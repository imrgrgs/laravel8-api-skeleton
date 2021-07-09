<?php

namespace App\Services;


use App\Models\Param;

use Illuminate\Support\Facades\DB;
use App\Repositories\ParamRepository;
use Illuminate\Database\Eloquent\Collection;


class ParamService
{
    /** @var  ParamRepository */
    private $paramRepository;

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
        $this->paramRepository = new ParamRepository();
    }



    /**
     * Save a new instance
     *
     * @param Array $input
     * @return app/Models/Param a new param saved
     */
    public function save(array $input, array $displayNames = null, array $descriptions = null, array $values = null)
    {
        $displayName = $this->prepareParamDisplayName($displayNames);
        $description = $this->prepareParamDescription($descriptions);
        $paramValues = $this->prepareParamValues($values);

        DB::beginTransaction();
        if (!$displayName) {
            $displayName = null;
        }
        $param = $this->paramRepository->create([
            'name' => ucwords($input['name']),
            'display_name' => $displayName,
        ]);

        if ($description) {
            $param->description()->create(['description' => $description]);
        }

        if ($paramValues) {
            $param->values()->createMany($paramValues);
        }
        DB::commit();
        return $param;
    }

    /**
     * Obtains an especific instance
     *
     * @param int $id unique auto increment id
     * @return Model
     */
    public function find($id, $columns = ['*'])
    {
        return $this->paramRepository->find($id, $columns);
    }

    /**
     * Update an param
     *
     * @param int $id unique auto increment id
     * @param Array $input
     * @return Model param updated
     */
    public function update(int $id, array $input, array $displayNames = null, array $descriptions = null)
    {
        DB::beginTransaction();
        $param = $this->paramRepository->update($input, $id);
        DB::commit();


        return $param;
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
        $qtdDel = $this->paramRepository->delete($id);
        DB::commit();
        return $qtdDel;
    }


    public function query($skip, $limit)
    {
        $params = $this->paramRepository->allOrFail(
            $skip,
            $limit
        );
        return $params;
    }
    /**
     * returns a ParamValue object colletion
     *
     * @param string $name a valid param name
     * @return Collection
     */
    public function getValuesByParamName(string $name): Collection
    {
        $param = $this->getParamByName($name);
        return $this->getValuesParam($param->id);
    }

    /**
     * Returns an array containig the codes of values
     *
     * @param string $name a param name to find the code values
     * @return array contains the code values Ex: ['code1', 'code2', ..., 'codeN']
     *
     */
    public function getValuesCodeByParamName(string $name): array
    {
        $param = $this->getParamByName($name);
        $values = $this->getValuesParam($param->id);
        $codes = [];
        foreach ($values as $value) {
            $codes[] = $value->code;
        }
        return $codes;
    }

    /**
     * get param by name of param
     *
     * @param string $name a param name
     * @return Param
     */
    public function getParamByName(string $name)
    {
        return $this->paramRepository->getByColumn('name', $name);
    }

    /**
     * Gets a param by id
     *
     * @param Bigint $id
     * @return Param an Param object
     */
    public function getParamById(int $id)
    {
        return $this->paramRepository->getByColumn('id', $id);
    }

    /**
     * returns a ParamValue object colletion
     *
     * @param Param $param a Param object
     * @return Collection containning paramvalue objects|null
     */
    public function getValuesParam($id): Collection
    {
        $param = $this->paramRepository->getByColumnOrFail('id', $id);
        return $param->values;
    }

    /**
     * returns a ParamValue object colletion
     *
     * @param Param $param a Param object
     * @return Collection containning paramvalue objects|null
     */
    public function getDescription(int $id)
    {
        $param = $this->paramRepository->getByColumnOrFail('id', $id);
        return $param->description;
    }

    private function prepareParamValues(array $values = [])
    {
        $paramValues = [];
        if ($values) {
            foreach ($values as $paramValue) {
                $paramValueNames = [];
                foreach ($paramValue['names'] as $key => $name) {
                    $paramValueNames[] = [$key => $name];
                }
                $paramValues[] = [
                    'code' => $paramValue['code'],
                    'name' => $paramValueNames,
                    'symbol' => $paramValue['symbol'],
                    'color' => $paramValue['color'],
                    'is_visible' => $paramValue['is_visible'],
                    'is_default' => $paramValue['is_default'],
                ];
            }
        }
        return $paramValues;
    }

    private function prepareParamDescription(array $descriptions = [])
    {
        $description = [];

        if ($descriptions) {
            foreach ($descriptions as $key => $value) {
                $description[] = [$key => $value];
            }
        }
        return $description;
    }

    private function prepareParamDisplayName(array $displayNames = [])
    {
        $displayName = [];

        if ($displayNames) {
            foreach ($displayNames as $key => $value) {
                $displayName[] = [$key => $value];
            }
        }
        return $displayName;
    }
}
