<?php

namespace wesrice\apitoolkit;

use Craft;
use craft\base\Plugin;
use craft\console\Application as ConsoleApplication;
use wesrice\apitoolkit\console\controllers\ApiToolkitController;
use wesrice\apitoolkit\services\OaiSchemata;
use wesrice\apitoolkit\behaviors\RequestBehavior;
use wesrice\apitoolkit\behaviors\ResponseBehavior;
use yii\base\Event;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;

class ApiToolkit extends Plugin
{
    public static $plugin;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        if (Craft::$app instanceof ConsoleApplication) {
            Craft::$app->controllerMap['apitoolkit'] = ApiToolkitController::class;
        }

        $this->setComponents([
            'oaiSchemata' => OaiSchemata::class,
        ]);

        Craft::$app->getRequest()->attachBehavior(
            'apiToolkitRequest', 
            RequestBehavior::className()
        );

        Craft::$app->getResponse()->attachBehavior(
            'apiToolkitResponse', 
            ResponseBehavior::className()
        );

        Event::on(
            UrlManager::class, 
            UrlManager::EVENT_REGISTER_SITE_URL_RULES, 
            [$this, 'registerUrlRules']
        );
    }

    protected function createSettingsModel()
    {
        $settings = new \wesrice\apitoolkit\models\Settings();

        $settings->validate();

        return new \wesrice\apitoolkit\models\Settings();
    }

    /**
     * Registers the site URL rules.
     *
     * @param RegisterUrlRulesEvent $event
     */
    public function registerUrlRules(RegisterUrlRulesEvent $event)
    {
        $event->rules = array_merge(
            $event->rules, 
            $this->oaiSchemata->getUrlRules()
        );
    }
}
