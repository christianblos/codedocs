<?php

/**
 * A simple class
 */
class Simple
{
    const SIMPLE_ONE = 1;

    /**
     * A reference
     */
    const SIMPLE_REF = self::SIMPLE_ONE;

    /**
     * @var mixed
     */
    public $notSet;

    private $isNull = null;

    protected $isTrue = true;

    public static $isFalse = false;

    private static $isInt = 1;

    protected static $isFloat = 123.456;

    public $isString = 'some string';

    public $isArray = [1, 2, 3 => 4, 'name' => 'me'];

    /**
     * @param int $a
     * @param int $b
     *
     * @return int
     */
    public function calc($a, &$b = 1):int
    {
        return $a + $b;
    }

    final protected static function foo(DateTime $dt, ...$rest)
    {
    }
}
