<?php
namespace samdark\hydrator\tests;

class TestClass
{
    private $privateField;
    protected $protectedField;
    public $publicField;

    private $constructorCalled = false;

    public function __construct()
    {
        $this->constructorCalled = true;
    }

    /**
     * @return mixed
     */
    public function getPrivateField()
    {
        return $this->privateField;
    }

    /**
     * @return mixed
     */
    public function getProtectedField()
    {
        return $this->protectedField;
    }

    public function getConstructorCalled()
    {
        return $this->constructorCalled;
    }
}