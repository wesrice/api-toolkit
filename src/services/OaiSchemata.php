<?php

namespace wesrice\apitoolkit\services;

use Craft;
use yii\base\Component;
use wesrice\apitoolkit\ApiToolkit;
use craft\web\Request;
use wesrice\apitoolkit\helpers\ArrayHelper;
use wesrice\apitoolkit\errors\PathOperationNotDefinedException;

class OaiSchemata extends Component
{
    /**
     * @var array
     */
    protected $schemata = [];

    /**
     * @var array
     */
    private $_normalizedSchemata = [];

    /**
     * @var array
     */
    private $_meta = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->schemata = array_map(function($schema) {
            return new $schema;
        }, ApiToolkit::getInstance()->getSettings()->schemata);

        $this->_normalizedSchemata = $this->_normalizeSchemata($this->schemata);
    }

    /**
     * Get url rules for all schemata.
     * 
     * @param  array|null $schemata Schemata
     * @return array                Url Rules
     */
    public function getUrlRules($schemata = null) : array
    {
        $schemata = $schemata ?: $this->schemata;

        $urlRules = [];

        foreach ($schemata as $schema) {
            $urlRules = ArrayHelper::merge(
                $urlRules, 
                $schema->getUrlRules()
            );
        }

        return $urlRules;
    }

    public function getFilters(Request $request) : array
    {
        $operation = $this->_getOperation(
            $request->method, 
            $request->getPathInfo()
        );

        $filters = ArrayHelper::get(
            $operation,
            implode('.', [
                self::CRAFT_META_KEY,
                self::APITOOLKIT_META_KEY,
                self::FILTERS_META_KEY,
            ])
        );

        return $filters;
    }

    private function _normalizeSchemata($schemata) : array
    {
        $normalizedPaths = [];

        foreach ($schemata as $schema) {
            $instance = $schema->getInstance();
            $this->_addMeta($instance);

            foreach ($instance['paths'] as $path => $operations) {
                $this->_addMeta($operations);

                foreach ($operations as $httpMethod => $operation) {
                    $this->_addMeta($operation);

                    $key = $this->_generateNormalizedPathKey(
                        $httpMethod, 
                        $schema->getInstanceBasePath() . $path
                    );

                    $operation = ArrayHelper::set(
                        $operation,
                        self::CRAFT_META_KEY,
                        $this->_meta
                    );

                    $operation = $this->_removeDuplicates($operation);

                    $normalizedPaths[$key] = $operation;
                }
            }
        }

        return $normalizedPaths;
    }

    private function _removeDuplicates(array $array) : array
    {
        $array = array_unique($array, SORT_REGULAR);

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->_removeDuplicates($value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    private function _addMeta(array $array)
    {
        $meta = ArrayHelper::get(
            $array,
            self::CRAFT_META_KEY
        );

        if ($meta) {
            $this->_meta = ArrayHelper::merge($this->_meta, $meta);
        }
    }

    private function _getOperation(string $httpMethod, string $uriPath) : array
    {
        $key = $this->_generateNormalizedPathKey($httpMethod, $uriPath);

        if (!isset($this->_normalizedSchemata[$key])) {
            throw new PathOperationNotDefinedException(
                sprintf(
                    'The `%s` http method is not defined for `%s` in any schema.',
                    $httpMethod,
                    $uriPath
                )
            );
        }

        return $this->_normalizedSchemata[$key];
    }

    private function _generateNormalizedPathKey(
        string $httpMethod, 
        string $uriPath
    ) : string {
        return sprintf(
            '%s__%s',
            strtoupper($httpMethod),
            ltrim($uriPath, '/')
        );
    }
}
