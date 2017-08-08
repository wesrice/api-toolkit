<?php

namespace wesrice\apitoolkit\controllers\api;

use Craft;
use wesrice\apitoolkit\base\ApiController;
use craft\web\Request;
use craft\web\Response;

class ElementsController extends ApiController
{
    public function actionIndex(Request $request, Response $response)
    {
        Craft::dd('!!!');
    }
}
