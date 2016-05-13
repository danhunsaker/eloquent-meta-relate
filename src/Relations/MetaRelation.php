<?php

namespace Danhunsaker\Eloquent\Relations;

use Illuminate\Database\Eloquent\Bulider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class MetaRelation extends Relation
{
    protected $relationships;

    public function __construct(Model $parent)
    {
        parent::__construct($parent->newQuery(), $parent);
    }

    /**
     * {@inheritdoc}
     */
    public function addConstraints()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function addEagerConstraints(array $models)
    {
        return $this->iterateRelationships('addEagerConstraints', [$models]);
    }

    /**
     * {@inheritdoc}
     */
    public function initRelation(array $models, $relation)
    {
        return $this->iterateRelationships('initRelation', [$models, $relation]);
    }

    /**
     * {@inheritdoc}
     */
    public function match(array $models, Collection $results, $relation)
    {
        return $this->iterateRelationships('match', [$models, $results, $relation]);
    }

    /**
     * {@inheritdoc}
     */
    public function getResults()
    {
        return $this->iterateRelationships('getResults', []);
    }

    /**
     * {@inheritdoc}
     */
    public function getEager()
    {
        return $this->iterateRelationships('getEager', []);
    }

    /**
     * {@inheritdoc}
     */
    public function touch()
    {
        return $this->iterateRelationships('touch', []);
    }

    /**
     * {@inheritdoc}
     */
    public function rawUpdate(array $attributes = [])
    {
        return $this->iterateRelationships('rawUpdate', [$attributes]);
    }

    /**
     * Add relationships to the internal collection, but only one level deep
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relationship The relationship to add or merge
     * @return self
     */
    public function merge(Relation $relationship)
    {
        if (is_null($this->relationships)) {
            $this->relationships = new Collection;
        }

        if (is_a($relationship, self::class)) {
            $this->relationships->merge($relationship->relationships);
        } else {
            $this->relationships->add($relationship);
        }

        return $this;
    }

    /**
     * Retrieve the internal collection
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelationsCollection()
    {
        return $this->relationships;
    }

    /**
     * {@inheritdoc}
     */
    public function __call($method, $parameters)
    {
        return $this->iterateRelationships($method, $parameters);
    }

    /**
     * Iterate over relationships, calling method, and collecting results
     */
    protected function iterateRelationships($method, $parameters)
    {
        return $this->relationships->reduce(function ($carry, $item) use ($method, $parameters) {
            try {
                $return = call_user_func_array([$item, $method], $parameters);
            } catch (\BadMethodCallException $e) {
                return $carry;
            }

            if (is_a($return, Model::class) || is_a($return, Builder::class) || is_null($return)) {
                $carry = $carry->add($return);
            } elseif (is_a($return, Collection::class)) {
                $carry = $carry->merge($return);
            } elseif (is_a($return, Relation::class)) {
                $item = $return;
            }

            return $carry;
        }, new Collection);
    }
}
