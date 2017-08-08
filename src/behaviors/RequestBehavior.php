<?php

namespace wesrice\apitoolkit\behaviors;

use Craft;

class RequestBehavior extends \yii\base\Behavior
{    
    public $filters = [];

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }
}
