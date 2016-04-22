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
     * @var string|null
     */
    private $currentFile;

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

    /**
     * @param string|null $file
     */
    public function setCurrentFile($file)
    {
        $this->currentFile = $file;
    }

    /**
     * @return string|null
     */
    public function getCurrentFile()
    {
        return $this->currentFile;
    }
}
