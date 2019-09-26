<?php

namespace Envant\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ModelMapper
{
    /**
     * Get a model by given alias
     *
     * @param string $modelName
     * @return Model
     */
    public static function getModel(string $modelName): Model
    {
        if (class_exists($modelName)) {
            return new $modelName();
        }

        $className = static::getClassName($modelName);
        return new $className();
    }

    /**
     * Get model's class name by given alias
     *
     * @param string $modelName
     * @return string
     */
    public static function getClassName(string $modelName): string
    {
        if (class_exists($modelName)) {
            return $modelName;
        }

        $morphMap = Relation::morphMap();
        if (isset($morphMap[$modelName])) {
            return $morphMap[$modelName];
        } else {
            throw new Exception("Model with alias '{$modelName}' doesn't exist");
        }
    }

    /**
     * Get an instance of a model by given alias and entity id
     *
     * @param string $modelName
     * @param integer $modelId
     * @return Model
     */
    public static function getEntity(string $modelName, int $modelId): Model
    {
        $model = static::getModel($modelName)->whereId($modelId)->firstOrFail();

        return $model;
    }

    /**
     * Get a list of available aliases
     *
     * @return array
     */
    public static function getAliases(): array
    {
        $morphMap = Relation::morphMap();
        return array_keys($morphMap);
    }
}
