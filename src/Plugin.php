<?php

namespace wesrice\apitoolkit;

use Craft;
use craft\console\Application as ConsoleApplication;
use wesrice\apitoolkit\console\controllers\ApiToolkitController;
use wesrice\apitoolkit\behaviors\RequestBehavior;
use wesrice\apitoolkit\behaviors\ResponseBehavior;
use yii\base\Event;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;

class Plugin extends \craft\base\Plugin
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (Craft::$app instanceof ConsoleApplication) {
            Craft::$app->controllerMap['apitoolkit'] = ApiToolkitController::class;
        }

        Craft::$app->request->attachBehavior(
            'apiToolkitRequest', 
            RequestBehavior::className()
        );

        Craft::$app->response->attachBehavior(
            'apiToolkitResponse', 
            ResponseBehavior::className()
        );

        // Event::on(
        //     UrlManager::class, 
        //     UrlManager::EVENT_REGISTER_SITE_URL_RULES, 
        //     [$this, 'registerUrlRules']
        // );
    }



    /**
     * Registers the site URL rules.
     *
     * @param RegisterUrlRulesEvent $event
     */
    public function registerUrlRules(RegisterUrlRulesEvent $event)
    {
        $event->rules = array_merge($event->rules, $this->getRules());
    }
}
