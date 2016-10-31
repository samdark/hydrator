<?php
namespace samdark\hydrator\tests;
use samdark\hydrator\Hydrator;

class HydratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $hydrator = new Hydrator([
            'privateField' => 'privateField',
            'protectedField' => 'protectedField',
            'publicField' => 'publicField',
        ]);

        /** @var TestClass $testObject */
        $testObject = $hydrator->hydrate([
            'privateField' => 1,
            'protectedField' => 2,
            'publicField' => 3,
        ], TestClass::class);

        self::assertEquals(1, $testObject->getPrivateField());
        self::assertEquals(2, $testObject->getProtectedField());
        self::assertEquals(3, $testObject->publicField);
        self::assertFalse($testObject->getConstructorCalled());
    }

    public function testGetData()
    {
        $testObject = new TestClass2(1, 2, 3);

        $hydrator = new Hydrator([
            'privateField' => 'privateField',
            'protectedField' => 'protectedField',
            'publicField' => 'publicField',
        ]);
        /** @var TestClass $testObject */
        $data = $hydrator->extract($testObject);

        self::assertEquals([
            'privateField' => 1,
            'protectedField' => 2,
            'publicField' => 3,
        ], $data);
    }
}
