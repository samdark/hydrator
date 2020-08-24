<?php
namespace samdark\hydrator\tests;

class TestChildClass2 extends TestClass2
{
    private $privateChildField;

    /**
     * TestClass2 constructor.
     * @param $privateField
     * @param $protectedField
     * @param $publicField
     */
    public function __construct($privateField, $protectedField, $publicField, $privateChildField)
    {
        parent::__construct($privateField, $protectedField, $publicField);
        $this->privateChildField = $privateChildField;
    }
}