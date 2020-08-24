<?php

namespace samdark\hydrator\tests\Benchmark;

use samdark\hydrator\Hydrator;

/**
 * @Iterations(10)
 * @Revs(1000)
 * @BeforeMethods({"before"})
 */
class HydratorBench
{

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * @var Example
     */
    private $instance;

    private $data = [
        'foo' => 1,
        'bar' => 2,
        'baz' => 3,
    ];

    public function before()
    {
        $this->hydrator = new Hydrator([
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz',
        ]);
        $this->instance = new Example();
    }

    public function benchHydrate()
    {
        $this->hydrator->hydrate($this->data, Example::class);
    }

    public function benchExtract()
    {
        $this->hydrator->extract($this->instance);
    }
}
