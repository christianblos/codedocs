<?php
namespace CodeDocs\ValueObject;

use InvalidArgumentException;

class Directory
{
    /**
     * @var string
     */
    private $dir;

    /**
     * @param string $dir
     * @param string $rootDir
     */
    public function __construct($dir, $rootDir)
    {
        if (!is_string($dir)) {
            throw new InvalidArgumentException('directory config must be a string');
        }

        $realRootDir = realpath($rootDir);

        if ($realRootDir === false) {
            throw new InvalidArgumentException(sprintf('directory %s does not exist', $rootDir));
        }

        if (strpos($dir, './') === 0) {
            $dir = $realRootDir . DIRECTORY_SEPARATOR . substr($dir, 2);
        } elseif ($dir === '.') {
            $dir = $realRootDir;
        }

        $this->dir = $dir;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->dir;
    }
}
