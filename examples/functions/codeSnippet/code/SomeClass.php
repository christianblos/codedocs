<?php

class SomeClass
{
    /**
     * @param string $name
     */
    public function sayHelloTo($name)
    {
        echo sprintf('Hello %s!', $name);
    }
}
