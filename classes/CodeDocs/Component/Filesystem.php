<?php
namespace CodeDocs\Component;

use CodeDocs\Exception\FileException;

class Filesystem
{
    /**
     * @param string $path
     *
     * @return bool
     */
    public function exists($path)
    {
        return file_exists($path);
    }

    /**
     * @param string $dir
     */
    public function mkdir($dir)
    {
        if (!@mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new FileException('could not create dir ' . $dir);
        }
    }

    /**
     * Create directory if not exists.
     *
     * @param string $dir
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

                $path = $dir . DIRECTORY_SEPARATOR . $file;

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
     */
    public function mirror($sourceDir, $targetDir)
    {
        if (!is_dir($sourceDir)) {
            throw new FileException(sprintf('directory %s does not exist', $sourceDir));
        }

        $this->purge($targetDir);
        $this->copy($sourceDir, $targetDir);
    }

    /**
     * Copy file and create directory if not exists.
     *
     * @param string $src
     * @param string $dest
     */
    public function copy($src, $dest)
    {
        if (is_dir($src)) {
            $this->ensureDir($dest);
            $files   = scandir($src);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $this->copy($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
                }
            }
        } else {
            $this->ensureDir(dirname($dest));

            copy($src, $dest);
        }
    }
}
