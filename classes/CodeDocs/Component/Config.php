<?php
namespace CodeDocs\Component;

use CodeDocs\ValueObject\Directory;

class Config
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var Source
     */
    private $source;

    /**
     * @param App    $app
     * @param Source $source
     */
    public function __construct(App $app, Source $source)
    {
        $this->app    = $app;
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getExportDir()
    {
        return $this->app->getExportDir();
    }

    /**
     * @return string
     */
    public function getBuildDir()
    {
        return $this->app->getConfigReader()->getBuildDir();
    }

    /**
     * @return Directory
     */
    public function getBaseDir()
    {
        return $this->source->baseDir;
    }

    /**
     * @return Directory
     */
    public function getDocsDir()
    {
        return $this->source->docsDir;
    }

    /**
     * @return Directory[]
     */
    public function getClassDirs()
    {
        return $this->source->classDirs;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getParam($name)
    {
        return $this->app->getConfigReader()->getParam($name);
    }
}
