<?php
namespace CodeDocs\Model;

class Source
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var string|null
     */
    private $docsDir;

    /**
     * @var string[]
     */
    private $classDirs = [];

    /**
     * @param string      $baseDir
     * @param string|null $docsDir
     * @param string[]    $classDirs
     */
    public function __construct($baseDir, $docsDir, array $classDirs)
    {
        $this->baseDir   = $baseDir;
        $this->docsDir   = $docsDir;
        $this->classDirs = $classDirs;
    }

    /**
     * @return string
     */
    public function getBaseDir()
    {
        return $this->baseDir;
    }

    /**
     * @return string|null
     */
    public function getDocsDir()
    {
        return $this->docsDir;
    }

    /**
     * @return string[]
     */
    public function getClassDirs()
    {
        return $this->classDirs;
    }
}
