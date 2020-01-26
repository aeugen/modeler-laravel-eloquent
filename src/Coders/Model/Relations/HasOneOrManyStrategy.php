<?php

namespace Aeugen\Modeler\Coders\Model\Relations;

use Illuminate\Support\Fluent;
use Aeugen\Modeler\Coders\Model\Model;
use Aeugen\Modeler\Coders\Model\Relation;

class HasOneOrManyStrategy implements Relation
{
    /**
     * @var \Aeugen\Modeler\Coders\Model\Relation
     */
    protected $relation;

    /**
     * HasManyWriter constructor.
     *
     * @param \Illuminate\Support\Fluent             $command
     * @param \Aeugen\Modeler\Coders\Model\Model $parent
     * @param \Aeugen\Modeler\Coders\Model\Model $related
     */
    public function __construct(Fluent $command, Model $parent, Model $related)
    {
        if (
            $related->isPrimaryKey($command) ||
            $related->isUniqueKey($command)
        ) {
            $this->relation = new HasOne($command, $parent, $related);
        } else {
            $this->relation = new HasMany($command, $parent, $related);
        }
    }

    /**
     * @return string
     */
    public function hint()
    {
        return $this->relation->hint();
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->relation->name();
    }

    /**
     * @return string
     */
    public function body()
    {
        return $this->relation->body();
    }
}
