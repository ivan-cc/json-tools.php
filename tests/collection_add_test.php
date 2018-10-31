<?php

use \PHPUnit\Framework\TestCase;
use \Iconify\JSONTools\Collection;

final class CollectionAddTest extends TestCase
{
    public function testAddingIcons()
    {
        $collection = new Collection();

        // Add item without prefix
        $this->assertFalse($collection->addIcon('foo', [
            'body'  => '<foo />'
        ]));
        $this->assertFalse($collection->iconExists('foo'));

        // Set prefix and try again
        $collection = new Collection('test-prefix');
        $this->assertTrue($collection->addIcon('foo', [
            'body'  => '<foo />'
        ]));
        $this->assertTrue($collection->iconExists('foo'));

        // Add few more icons
        $this->assertTrue($collection->addIcon('bar', [
            'body'  => '<bar />'
        ]));
        $this->assertTrue($collection->addIcon('baz', [
            'body'  => '<baz />'
        ]));

        // Add aliases
        $this->assertTrue($collection->addAlias('bar2', 'bar', [
            'rotate'    => 1
        ]));
        $this->assertTrue($collection->addAlias('bar3', 'bar2', [
            'hFlip' => true
        ]));
        $this->assertFalse($collection->addAlias('foo2', 'foo1', [
            'hFlip'    => true
        ]));

        // Get JSON data
        $this->assertEquals([
            'prefix'    => 'test-prefix',
            'icons' => [
                'foo'   => [
                    'body'  => '<foo />'
                ],
                'bar'   => [
                    'body'  => '<bar />'
                ],
                'baz'   => [
                    'body'  => '<baz />'
                ]
            ],
            'aliases'   => [
                'bar2'  => [
                    'parent'    => 'bar',
                    'rotate'    => 1
                ],
                'bar3'  => [
                    'parent'    => 'bar2',
                    'hFlip' => true
                ]
            ]
        ], $collection->getIcons());

        // Get everything except 'foo'
        $this->assertEquals([
            'prefix'    => 'test-prefix',
            'icons' => [
                'bar'   => [
                    'body'  => '<bar />'
                ],
                'baz'   => [
                    'body'  => '<baz />'
                ]
            ],
            'aliases'   => [
                'bar2'  => [
                    'parent'    => 'bar',
                    'rotate'    => 1
                ],
                'bar3'  => [
                    'parent'    => 'bar2',
                    'hFlip' => true
                ]
            ]
        ], $collection->getIcons(['bar3', 'baz']));
    }
}

