<?php
namespace CodeDocs;

use CodeDocs\Helper\Filesystem;
use RuntimeException;
use SplFileInfo;

abstract class BaseParser
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string[]    $dirs
     * @param string      $fileMatch
     * @param string|null $cacheDir
     *
     * @return array
     * @throws RuntimeException
     */
    protected function parseFiles(array $dirs, $fileMatch, $cacheDir = null)
    {
        $files   = $this->filesystem->getFilesOfDirs($dirs, $fileMatch);
        $results = [];

        $tmpFile  = $cacheDir !== null ? $cacheDir . '/' . $this->getCacheFile() : null;
        $oldCache = [];

        if ($tmpFile !== null && file_exists($tmpFile)) {
            $oldCache = unserialize(file_get_contents($tmpFile));
        }

        foreach ($files as $file) {
            $realPath = $file->getRealPath();
            if (!$realPath) {
                return [];
            }

            $hash = $this->filesystem->getFileHash($realPath);

            if (isset($oldCache[$hash])) {
                $fileResult = $this->fromCache($oldCache[$hash], $file);
                unset($oldCache[$hash]);
            } else {
                $fileResult = $this->parseFile($realPath);
            }

            $results[$hash] = $fileResult;
        }

        if ($tmpFile !== null) {
            $this->filesystem->writeFile(
                $tmpFile,
                serialize($results)
            );
        }

        return $results;
    }

    /**
     * @return string
     */
    abstract protected function getCacheFile();

    /**
     * @param string $path
     *
     * @return mixed
     */
    abstract protected function parseFile($path);

    /**
     * @param mixed       $result
     * @param SplFileInfo $file
     *
     * @return mixed
     */
    protected function fromCache($result, SplFileInfo $file)
    {
        return $result;
    }
}
