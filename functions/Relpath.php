<?php
namespace CodeDocs\Func;

use CodeDocs\Doc\MarkupFunction;

/**
 * Returns a path relative to the baseDir.
 */
class Relpath extends MarkupFunction
{
    const FUNC_NAME = 'relpath';

    /**
     * @param string $of        The absolute path
     * @param string $separator The separator
     *
     * @return string
     */
    public function __invoke($of, $separator = '/')
    {
        if (!realpath($of)) {
            return $of;
        }

        $from     = $this->splitPath($this->state->config->getBaseDir());
        $to       = $this->splitPath(realpath($of));
        $relParts = [];

        // Find how far the path is the same
        $i = 0;
        while (isset($from[$i]) && isset($to[$i])) {
            if ($from[$i] !== $to[$i]) {
                break;
            }
            $i++;
        }

        // Add '..' until the path is the same
        $j = count($from) - 1;
        while ($i <= $j) {
            $relParts[] = '..';
            $j--;
        }

        // Go to folder from where it starts differing
        while (isset($to[$i])) {
            $relParts[] = $to[$i];
            $i++;
        }

        return implode($separator, $relParts);
    }

    /**
     * @param string $path
     *
     * @return string[]
     */
    public function splitPath($path)
    {
        $path = rtrim($path, '/\\');

        return preg_split('/(\/|\\\\)+/', $path);
    }
}
