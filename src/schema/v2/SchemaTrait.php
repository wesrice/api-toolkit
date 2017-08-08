<?php

namespace wesrice\apitoolkit\schema\v2;

trait SchemaTrait
{
    /**
     * @var string
     */
    protected $path;

    /**
     * Get the absolute path to the schema file.
     * 
     * @return string Absolute Path
     */
    public function getAbsolutePath() : string
    {
        return $this->path;
    }

    /**
     * Get the instance of the schema.
     * 
     * @return array Instance
     */
    public function getInstance() : array
    {
        return $this->instance;
    }

    public function isValidOaiV2Spec() 
    {
        return true;
    }

    /**
     * Get the url rules for paths and operations defined in the schema.
     * 
     * @return array Path Rules
     */
    public function getUrlRules() : array
    {
        return $this->urlRules;
    }

    public function getInstancePaths()
    {
        return $this->getInstance()['paths'];
    }

    public function getInstanceBasePath()
    {
        return $this->getInstance()['basePath'];
    }
}
