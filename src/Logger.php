<?php
namespace CodeDocs;

class Logger
{
    /**
     * @var int
     */
    private $depth = 0;

    /**
     * @var string[]
     */
    private $replaces = [];

    /**
     * @var string[]
     */
    private static $colors = [
        '<black>'   => "\033[0;30m",
        '<red>'     => "\033[0;31m",
        '<green>'   => "\033[0;31m",
        '<yellow>'  => "\033[0;33m",
        '<blue>'    => "\033[0;34m",
        '<magenta>' => "\033[0;34m",
        '<cyan>'    => "\033[0;36m",
        '<white>'   => "\033[0;37m",
        '<reset>'   => "\033[0m",
    ];

    /**
     *
     */
    public function __construct()
    {
        $this->replaces = self::$colors;
    }

    /**
     *
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }

    /**
     *
     */
    public function disableColors()
    {
        $this->replaces = array_fill(0, count(self::$colors), '');
    }

    /**
     * @param int $depth
     *
     * @return bool
     */
    public function hasDepth($depth)
    {
        return $depth <= $this->depth;
    }

    /**
     * @param int    $depth
     * @param string $message
     */
    public function log($depth, $message)
    {
        if ($depth > $this->depth) {
            return;
        }

        if ($depth === 0) {
            echo '- ';
        } elseif ($depth > 0) {
            echo str_repeat('   ', $depth - 1);
            echo '   > ';
        }

        $message = str_replace(array_keys(self::$colors), $this->replaces, $message);

        echo $message . PHP_EOL;
    }

    /**
     * @param string $path
     * @param int    $at
     */
    public function logErrorPosition($path, $at)
    {
        if (!$path) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES);

        if ($lines === false) {
            // just a fallback.. who knows
            $errorDetail = '';
            $errorPos    = $at;
        } else {
            $errorDetail = $this->getErrorDetail($lines, $at, $lineNr, $linePos);
            $errorPos    = $lineNr . ':' . $linePos;
        }

        $this->log(-1, sprintf(
            'in %s/<red>%s at %s<reset>%s',
            dirname($path),
            basename($path),
            $errorPos,
            $errorDetail
        ));
    }

    /**
     * @param array $lines
     * @param int   $at
     * @param int   $lineNr
     * @param int   $linePos
     *
     * @return string
     */
    protected function getErrorDetail(array $lines, $at, &$lineNr, &$linePos)
    {
        $totalLength = 0;
        $lineNr      = 0;
        $linePos     = 0;
        $line        = '';

        foreach ($lines as $idx => $line) {
            $length = strlen($line);
            $totalLength += $length;

            if ($totalLength >= $at) {
                $lineNr  = 1 + $idx;
                $linePos = $length - ($totalLength - $at);
                break;
            }
        }

        $content = PHP_EOL . PHP_EOL . '<red>><reset> ' . $line . PHP_EOL
            . ' ' . ($linePos ? str_repeat(' ', $linePos) : '') . '<red>^<reset>' . PHP_EOL;

        return $content;
    }

}
