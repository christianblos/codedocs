<?php
namespace CodeDocs\Type;

class Path
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     * @param string $baseDir
     */
    public function __construct($path, $baseDir)
    {
        if ($path === '..' || strpos($path, './') === 0 || strpos($path, '../') === 0) {
            $path = $baseDir . DIRECTORY_SEPARATOR . $path;
        } elseif ($path === '.') {
            $path = $baseDir;
        }

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->path;
    }
}
