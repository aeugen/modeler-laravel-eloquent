<?php

namespace Aeugen\Modeler\Coders\Model;

use ArrayIterator;
use Illuminate\Support\Arr;
use IteratorAggregate;

class ModelManager implements IteratorAggregate
{
    /**
     * @var \Aeugen\Modeler\Coders\Model\Factory
     */
    protected $factory;

    /**
     * @var \Aeugen\Modeler\Coders\Model\Model[]
     */
    protected $models = [];

    /**
     * ModelManager constructor.
     *
     * @param \Aeugen\Modeler\Coders\Model\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string                                     $schema
     * @param string                                     $table
     * @param \Aeugen\Modeler\Coders\Model\Mutator[] $mutators
     * @param bool                                       $withRelations
     *
     * @return \Aeugen\Modeler\Coders\Model\Model
     */
    public function make($schema, $table, $mutators = [], $withRelations = true)
    {
        $mapper = $this->factory->makeSchema($schema);

        $blueprint = $mapper->table($table);

        if (Arr::has($this->models, $blueprint->qualifiedTable())) {
            return $this->models[$schema][$table];
        }

        $model = new Model($blueprint, $this->factory, $mutators, $withRelations);

        if ($withRelations) {
            $this->models[$schema][$table] = $model;
        }

        return $model;
    }

    /**
     * Get Models iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->models);
    }
}
