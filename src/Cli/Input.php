<?php
namespace CodeDocs\Cli;

class Input
{
    /**
     * @var string[]
     */
    private $names = [];

    /**
     * @var string[][]
     */
    private $options = [];

    /**
     * @var string[]
     */
    private $arguments = [];

    /**
     * @var int
     */
    private $verbosity = 0;

    /**
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->parse($args);
    }

    /**
     * @param string $name
     *
     * @return string|bool|null
     */
    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            if (in_array($name, $this->names, true)) {
                return true;
            }

            return null;
        }

        return end($this->options[$name]);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function getOptionArray($name)
    {
        if (!isset($this->options[$name])) {
            return [];
        }

        return $this->options[$name];
    }

    /**
     * @return string[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        $params = [];

        foreach ($this->names as $name) {
            if (isset($this->options[$name])) {
                $params[$name] = $this->getOption($name);
            } else {
                $params[$name] = true;
            }
        }

        return $params;
    }

    /**
     * @return int
     */
    public function getVerbosity()
    {
        return $this->verbosity;
    }

    /**
     * @param array $args
     */
    private function parse(array $args)
    {
        $name = null;

        foreach ($args as $idx => $arg) {
            if (preg_match('/^-(v+)$/', $arg, $matches)) {
                $this->verbosity = strlen($matches[1]);
            } elseif (strpos($arg, '--') === 0) {
                $name          = substr($arg, 2);
                $this->names[] = $name;
            } elseif ($name !== null) {
                $this->setOption($name, $arg);
                $name = null;
            } elseif ($idx > 0) {
                $this->arguments[] = $arg;
            }
        }
    }

    /**
     * @param string      $name
     * @param string|bool $value
     */
    private function setOption($name, $value)
    {
        if (!isset($this->options[$name])) {
            $this->options[$name] = [$value];
        } else {
            $this->options[$name][] = $value;
        }
    }
}
