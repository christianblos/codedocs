<?php
namespace CodeDocs\Helper;

use AppendIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RuntimeException;
use SplFileInfo;

class Filesystem
{
    /**
     * @param array  $dirs
     * @param string $match
     *
     * @return SplFileInfo[]|RegexIterator
     */
    public function getFilesOfDirs(array $dirs, $match = '/\.php$/')
    {
        $iterator = new AppendIterator();

        foreach ($dirs as $dir) {
            $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)));
        }

        return new RegexIterator($iterator, $match);
    }

    /**
     * @param string $dir
     *
     * @throws RuntimeException
     */
    public function mkdir($dir)
    {
        if (!@mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException('could not create dir ' . $dir);
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function readFile($path)
    {
        return file_get_contents($path);
    }

    /**
     * @param string $file
     * @param string $content
     *
     * @throws RuntimeException
     */
    public function writeFile($file, $content)
    {
        $this->ensureDir(dirname($file));

        file_put_contents($file, $content);
    }

    /**
     * Create directory if not exists.
     *
     * @param string $dir
     *
     * @throws RuntimeException
     */
    public function ensureDir($dir)
    {
        if (!is_dir($dir)) {
            $this->mkdir($dir);
        }
    }

    /**
     * Delete all files inside a directory.
     *
     * @param string $dir
     *
     * @return void
     */
    public function purge($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = scandir($dir);

        if ($files !== false) {
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $path = $dir . '/' . $file;

                if (is_dir($path)) {
                    $this->purge($path);
                    rmdir($path);
                } else {
                    unlink($path);
                }
            }
        }
    }

    /**
     * @param string $sourceDir
     * @param string $targetDir
     *
     * @throws RuntimeException
     */
    public function mirror($sourceDir, $targetDir)
    {
        if (!is_dir($sourceDir)) {
            throw new RuntimeException(sprintf('directory %s does not exist', $sourceDir));
        }

        $this->purge($targetDir);
        $this->copy($sourceDir, $targetDir);
    }

    /**
     * Copy file and create directory if not exists.
     *
     * @param string $src
     * @param string $dest
     *
     * @throws RuntimeException
     */
    public function copy($src, $dest)
    {
        if (is_dir($src)) {
            $this->ensureDir($dest);
            $files = scandir($src);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $this->copy($src . '/' . $file, $dest . '/' . $file);
                }
            }
        } else {
            $this->ensureDir(dirname($dest));

            copy($src, $dest);
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getFileHash($path)
    {
        return sha1_file($path);
    }
}
