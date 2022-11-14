<?php
namespace CodeDocs\Integration;

use NotExists\AnotherInterface;

abstract class Base
{
    use SomeTrait;
}

interface InterFirst
{

}

interface InterSecond extends AnotherInterface
{

}

final class Complex extends Base implements InterFirst, InterSecond
{

}

trait SomeTrait {

}

enum SomeEnum
{
    use SomeTrait;
}
