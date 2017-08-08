<?php

namespace wesrice\apitoolkit\controllers;

use Craft;
use wesrice\apitoolkit\base\Controller;
use craft\web\Request;
use craft\web\Response;

class DefaultController extends Controller
{
    public function actionIndex(Request $request, Response $response, int $page)
    {
        Craft::dd('!!');
    }
}
