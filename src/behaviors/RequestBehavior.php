<?php

namespace wesrice\apitoolkit\behaviors;

use Craft;
use wesrice\apitoolkit\ApiToolkit;

class RequestBehavior extends \yii\base\Behavior
{
    /**
     * @var \wesrice\apitoolkit\services\OaiSchemata
     */
    protected $oaiSchemataService;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->oaiSchemataService = ApiToolkit::getInstance()->oaiSchemata;
    }

    public function getFilters()
    {
        return $this->oaiSchemataService->getFilters($this->owner);
    }
}
