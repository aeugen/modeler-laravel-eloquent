<?php

namespace Aeugen\Modeler\Coders\Model\Relations;

use Illuminate\Support\Str;

class ReferenceFactory
{
    /**
     * @var array
     */
    protected $related;

    /**
     * @var \Aeugen\Modeler\Coders\Model\Model
     */
    protected $parent;

    /**
     * @var \Aeugen\Modeler\Coders\Model\Model[]
     */
    protected $references = [];

    /**
     * ReferenceFactory constructor.
     *
     * @param array                                  $related
     * @param \Aeugen\Modeler\Coders\Model\Model $parent
     */
    public function __construct($related, $parent)
    {
        $this->related = (array) $related;
        $this->parent = $parent;
    }

    /**
     * @return \Aeugen\Modeler\Coders\Model\Relation[]
     */
    public function make()
    {
        $relations = [];

        if ($this->hasPivot()) {
            foreach ($this->references as $reference) {
                $relation = new BelongsToMany($this->getRelatedReference(), $reference['command'], $this->parent, $this->getRelatedModel(), $reference['model']);
                $relations[$relation->name()] = $relation;
            }
        }

        $relation = new HasOneOrManyStrategy($this->getRelatedReference(), $this->parent, $this->getRelatedModel());

        $relations[$relation->name()] = $relation;

        return $relations;
    }

    /**
     * @return bool
     */
    protected function hasPivot()
    {
        $pivot = $this->getRelatedBlueprint()->table();
        $firstRecord = $this->parent->getRecordName();

        // See whether this potencial pivot table has the parent record name in it.
        // Not sure whether we should only take into account composite primary keys.
        if (
            !Str::contains($pivot, $firstRecord)
        ) {
            return false;
        }

        $pivot = str_replace($firstRecord, '', $pivot);

        foreach ($this->getRelatedBlueprint()->relations() as $reference) {
            if ($reference == $this->getRelatedReference()) {
                continue;
            }

            $target = $this->getRelatedModel()->makeRelationModel($reference);

            // Check whether this potential pivot table has the target record name in it
            if (Str::contains($pivot, $target->getRecordName())) {
                $this->references[] = [
                    'command' => $reference,
                    'model'   => $target,
                ];
            }
        }

        return count($this->references) > 0;
    }

    /**
     * @return \Illuminate\Support\Fluent
     */
    protected function getRelatedReference()
    {
        return $this->related['reference'];
    }

    /**
     * @return \Aeugen\Modeler\Coders\Model\Model
     */
    protected function getRelatedModel()
    {
        return $this->related['model'];
    }

    /**
     * @return \Aeugen\Modeler\Meta\Blueprint
     */
    protected function getRelatedBlueprint()
    {
        return $this->related['blueprint'];
    }
}
