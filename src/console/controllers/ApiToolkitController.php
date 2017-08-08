<?php

namespace wesrice\apitoolkit\console\controllers;

use Craft;
use yii\console\Controller;
use yii\helpers\Console;

class ApiToolkitController extends Controller
{
    public function actionExportTokens()
    {
        var_dump(Craft::$app->getSites()->getPrimarySite());
    }
}
