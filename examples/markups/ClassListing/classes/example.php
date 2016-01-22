<?php

interface InterfaceA {}

interface InterfaceB {}

abstract class ParentClass {}

class A extends ParentClass implements InterfaceA {}

class B implements InterfaceA, InterfaceB {}
