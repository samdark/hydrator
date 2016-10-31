<?php
namespace samdark\hydrator\tests;

class TestClass2
{
    private $privateField;
    protected $protectedField;
    public $publicField;

    /**
     * TestClass2 constructor.
     * @param $privateField
     * @param $protectedField
     * @param $publicField
     */
    public function __construct($privateField, $protectedField, $publicField)
    {
        $this->privateField = $privateField;
        $this->protectedField = $protectedField;
        $this->publicField = $publicField;
    }
}