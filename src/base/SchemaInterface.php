<?php

namespace wesrice\apitoolkit\base;

interface SchemaInterface
{
    public function getInstance() : array;

    public function getUrlRules() : array;
}
