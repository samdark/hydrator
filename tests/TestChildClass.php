<?php
namespace samdark\hydrator\tests;

class TestChildClass extends TestClass
{
    private $privateChildField;

    /**
     * @return mixed
     */
    public function getPrivateChildField()
    {
        return $this->privateChildField;
    }
}