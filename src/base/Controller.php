<?php

namespace wesrice\apitoolkit\base;

use craft\web\Request;
use craft\web\Response;
use yii\helpers\ArrayHelper;
use Craft;

class Controller extends \craft\web\Controller
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
            []
        );
    }

    /**
     * @inheritdoc
     */
    public function bindActionParams($action, $params)
    {
        return array_merge([
            'request' => Craft::$app->request,
            'response' => Craft::$app->response,
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
