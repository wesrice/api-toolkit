<?php

namespace wesrice\apitoolkit\base;

use Craft;
use craft\helpers\ArrayHelper;

class ApiController extends \craft\web\Controller
{
    /**
     * @inheritdoc
     */
    protected $allowAnonymous = true;

    /**
     * @inheritdoc
     */
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            Craft::$app->getRequest()->getFilters()
        );
    }

    /**
     * @inheritdoc
     */
    public function bindActionParams($action, $params)
    {
        return array_merge([
            'request' => Craft::$app->getRequest(),
            'response' => Craft::$app->getResponse(),
        ], $params);
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $response)
    {
        $response = parent::afterAction($action, $response);
        
        return $response->serializeData();
    }
}
