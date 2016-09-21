<?php
namespace CodeDocs;

use CodeDocs\Exception\ConfigException;

class ConfigLoader
{
    const DEFAULT_FILE = 'codedocs.config.php';

    /**
     * @param string $execDir
     * @param array  $configFiles
     *
     * @return Config
     * @throws ConfigException
     */
    public function load($execDir, array $configFiles)
    {
        $includeFiles = $this->getIncludeFiles($execDir, $configFiles);

        $config = new Config();

        foreach ($includeFiles as $includeFile) {
            include $includeFile;
        }

        return $config;
    }

    /**
     * @param string $execDir
     * @param array  $configFiles
     *
     * @return string[]
     * @throws ConfigException
     */
    private function getIncludeFiles($execDir, array $configFiles)
    {
        $includeFiles = [];

        foreach ($configFiles as $configFile) {
            $file = realpath($configFile);

            // if file not exists, try relative path
            if (!$file) {
                $file = realpath($execDir . '/' . $configFile);
            }

            if (!$file) {
                throw new ConfigException(sprintf('config file "%s" does not exist', $file));
            }

            $includeFiles[] = $file;
        }

        if (empty($configFiles)) {
            $file = realpath($execDir . '/' . self::DEFAULT_FILE);

            if (!$file) {
                throw new ConfigException(sprintf('config file "%s" not found', $file));
            }

            $includeFiles[] = $file;
        }

        return $includeFiles;
    }
}
