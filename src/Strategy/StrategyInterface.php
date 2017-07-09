<?php

namespace Samdark\Hydrator\Strategy;

interface StrategyInterface
{
    public function extract($value);

    public function hydrate($value);
}