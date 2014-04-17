<?php

/**
 * ArrayObjectTest case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace Utility;

use Slick\Utility\ArrayObject;

/**
 * ArrayObjectTest case
 *
 * @package    Test\Utility
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class ArrayObjectTest extends \Codeception\TestCase\Test
{

    /**
     * Serializind and unserializing ArrayObjects
     * @test
     */
    public function serializingArrayObjects()
    {
        $data = [
            'one', 'two', 'three'
        ];
        $object = new ArrayObject($data);
        $serialized = serialize($object);

        /** @var ArrayObject $newObject */
        $newObject = unserialize($serialized);

        $this->assertEquals($data, $newObject->getArrayCopy());
        $this->assertEquals($object, $newObject);

    }
}