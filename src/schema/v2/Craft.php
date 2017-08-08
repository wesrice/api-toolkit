<?php

namespace wesrice\apitoolkit\schema\v2;

use wesrice\apitoolkit\base\SchemaInterface;
use Craft as CraftApp;
use craft\helpers\Json;
use wesrice\apitoolkit\helpers\ArrayHelper;

class Craft extends \craft\base\Model implements SchemaInterface
{
    use SchemaTrait;

    const CRAFT_META_KEY = 'x-craftcms';

    const APITOOLKIT_META_KEY = 'apitoolkit';

    const CONTROLLER_META_KEY = 'controller';

    const ACTION_META_KEY = 'action';

    const FILTERS_META_KEY = 'filters';

    /**
     * @var string
     */
    protected $version = 'v2';

    /**
     * @var string
     */
    protected $name = 'craft';

    /**
     * @var array
     */
    protected $sourceParts = [
        '@wesrice',
        'apitoolkit',
        'resources',
        'oai-schema',
        '%s',
        '%s.%s',
    ];

    /**
     * @var string
     */
    protected $fileType = 'json';

    /**
     * @var array
     */
    protected $instance = [];

    protected $urlRules = [];

    protected $_normalizedPaths = [];

    protected $_meta;

    public function rules()
    {
        return [
            [['instance'], 'required'],
            [['instance'], 'isValidOaiV2Spec'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $source = CraftApp::getAlias(
            sprintf(
                implode(
                    DIRECTORY_SEPARATOR, 
                    $this->sourceParts
                ),
                $this->version,
                $this->name,
                $this->fileType
            )
        );

        $this->instance = Json::decode(
            file_get_contents($source), 
            true
        );

        // $this->_normalizedPaths = $this->_normalizePaths($this->instance);

        // CraftApp::dd($this->_normalizedPaths);
    }

    public function getUrlRules() : array 
    {
        foreach ($this->instance['paths'] as $path => $httpMethods) {
            foreach ($httpMethods as $httpMethod => $operation) {

            }
        }

        return [
            'GET api/elements' => 'apitoolkit/api/elements/index',
            'PUT,POST post/<id:\d+>' => 'post/update',
            'DELETE post/<id:\d+>' => 'post/delete',
        ];
    }

    // protected function _normalizePaths(array $instance)
    // {
    //     $normalizedPaths = [];

    //     $this->_addMeta($instance);

    //     foreach ($instance['paths'] as $path => $operations) {
    //         $this->_addMeta($instance);
    //         $operations = $this->_addMeta($operations);

    //         foreach ($operations as $httpMethod => $operation) {
    //             $operation = $this->_addMeta($operation);

    //             $key = $this->_generateNormalizedPathKey(
    //                 $httpMethod, 
    //                 $instance['basePath'] . $path
    //             );

    //             $operation = ArrayHelper::set(
    //                 $operation,
    //                 self::CRAFT_META_KEY,
    //                 $this->_meta
    //             );

    //             $operation = $this->_removeDuplicates($operation);

    //             $normalizedPaths[$key] = $operation;
    //         }
    //     }

    //     return $normalizedPaths;
    // }

    // private function _removeDuplicates(array $array) : array
    // {
    //     $array = array_unique($array, SORT_REGULAR);

    //     foreach ($array as $key => $value) {
    //         if (is_array($value)) {
    //             $array[$key] = $this->_removeDuplicates($value);
    //         } else {
    //             $array[$key] = $value;
    //         }
    //     }

    //     return $array;
    // }

    // private function _addMeta(array $array)
    // {
    //     $meta = ArrayHelper::get(
    //         $array,
    //         self::CRAFT_META_KEY
    //     );

    //     if ($meta) {
    //         $this->_meta = ArrayHelper::merge($this->_meta, $meta);
    //     }

    //     unset($array[self::CRAFT_META_KEY]);

    //     return $array;
    // }

    // private function _generateNormalizedPathKey(
    //     string $httpMethod, 
    //     string $uriPath
    // ) : string {
    //     return sprintf(
    //         '%s__%s',
    //         strtoupper($httpMethod),
    //         ltrim($uriPath, '/')
    //     );
    // }
}
