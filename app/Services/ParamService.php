<?php

namespace App\Services;

use App\Models\Param;
use App\Models\ParamValue;
use Illuminate\Database\Eloquent\Collection;


class ParamService
{
    public function __construct()
    {
    }

    /**
     * returns a ParamValue object colletion
     *
     * @param string $name a valid param name
     * @return Collection
     */
    public static function getValuesByParamName(string $name): Collection
    {
        $param = self::getParamByName($name);
        return self::getValuesParam($param);
    }

    /**
     * Returns an array containig the codes of values
     *
     * @param string $name a param name to find the code values
     * @return array contains the code values Ex: ['code1', 'code2', ..., 'codeN']
     *
     */
    public static function getValuesCodeByParamName(string $name): array
    {
        $param = self::getParamByName($name);
        $values = self::getValuesParam($param);
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
    public static function getParamByName(string $name): Param
    {
        return Param::where('name', $name)->first();
    }

    /**
     * Gets a param by id
     *
     * @param Bigint $id
     * @return Param an Param object
     */
    public static function getParamById(int $id): Param
    {
        return Param::where('id', $id)->first();
    }

    /**
     * returns a ParamValue object colletion
     *
     * @param Param $param a Param object
     * @return Collection containning paramvalue objects|null
     */
    public static function getValuesParam(Param $param): Collection
    {
        return ParamValue::where('param_id', $param->id)->get();
    }
}
