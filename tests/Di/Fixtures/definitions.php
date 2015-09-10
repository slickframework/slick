<?php

use Slick\Di\Definition\ObjectDefinition;

return [
    'def.foo' => 'bar',
    'def.bar' => '@def.foo',
    'def.baz' => function() { return 'baz';},
    'def.std' => ObjectDefinition::create('Slick\Tests\Di\Fixtures\Dummy')
        ->setConstructArgs(['@def.foo'])
        ->setMethod('setValue', ['@def.bar'])
        ->setProperty('name', '@def.baz')
];