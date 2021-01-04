<?php
require 'vendor/autoload.php';
use Roave\BetterReflection\BetterReflection;

$classInfo = (new BetterReflection)
    ->classReflector()
    ->reflect(\Foo\Bar\MyClass::class);

var_dump($classInfo);