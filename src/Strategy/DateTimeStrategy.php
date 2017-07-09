<?php

namespace Samdark\Hydrator\Strategy;

class DateTimeStrategy implements StrategyInterface
{
    public function hydrate($value)
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', $value);
    }

    public function extract($value)
    {
        return $value->format('Y-m-d H:i:s');
    }
}