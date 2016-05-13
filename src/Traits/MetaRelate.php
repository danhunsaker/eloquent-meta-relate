<?php

namespace Danhunsaker\Eloquent\Traits;

use Danhunsaker\Eloquent\Relations\MetaRelation;

trait MetaRelate
{
    protected function createMetaRelation()
    {
        return new MetaRelation($this);
    }

    /**
     * Define a one-to-one relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        if (is_array($related)) {
            $relationship = $this->createMetaRelation();

            foreach ($related as $relative) {
                $relationship->merge(parent::hasOne($relative, $foreignKey, $localKey));
            }
        } else {
            $relationship = parent::hasOne($related, $foreignKey, $localKey);
        }

        return $relationship;
    }

    /**
     * Define a polymorphic one-to-one relationship.
     *
     * @param  string  $related
     * @param  string  $name
     * @param  string  $type
     * @param  string  $id
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function morphOne($related, $name, $type = null, $id = null, $localKey = null)
    {
        if (is_array($related)) {
            $relationship = $this->createMetaRelation();

            foreach ($related as $relative) {
                $relationship->merge(parent::morphOne($relative, $name, $type, $id, $localKey));
            }
        } else {
            $relationship = parent::morphOne($related, $name, $type, $id, $localKey);
        }

        return $relationship;
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $otherKey
     * @param  string  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null)
    {
        if (is_null($relation)) {
            list($current, $caller) = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

            $relation = $caller['function'];
        }

        if (is_array($related)) {
            $relationship = $this->createMetaRelation();

            foreach ($related as $relative) {
                $relationship->merge(parent::belongsTo($relative, $foreignKey, $otherKey, $relation));
            }
        } else {
            $relationship = parent::belongsTo($related, $foreignKey, $otherKey, $relation);
        }

        return $relationship;
    }

    /**
     * Define a one-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        if (is_array($related)) {
            $relationship = $this->createMetaRelation();

            foreach ($related as $relative) {
                $relationship->merge(parent::hasMany($relative, $foreignKey, $localKey));
            }
        } else {
            $relationship = parent::hasMany($related, $foreignKey, $localKey);
        }

        return $relationship;
    }

    /**
     * Define a has-many-through relationship.
     *
     * @param  string  $related
     * @param  string  $through
     * @param  string|null  $firstKey
     * @param  string|null  $secondKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function hasManyThrough($related, $through, $firstKey = null, $secondKey = null, $localKey = null)
    {
        if (is_array($related) || is_array($through)) {
            $relationship = $this->createMetaRelation();

            foreach ((array) $related as $relative) {
                foreach ((array) $through as $through_one) {
                    $relationship->merge(parent::hasManyThrough($relative, $through_one, $firstKey, $secondKey, $localKey));
                }
            }
        } else {
            $relationship = parent::hasManyThrough($related, $through_one, $firstKey, $secondKey, $localKey);
        }

        return $relationship;
    }

    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $name
     * @param  string  $type
     * @param  string  $id
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function morphMany($related, $name, $type = null, $id = null, $localKey = null)
    {
        if (is_array($related)) {
            $relationship = $this->createMetaRelation();

            foreach ($related as $relative) {
                $relationship->merge(parent::morphMany($relative, $name, $type, $id, $localKey));
            }
        } else {
            $relationship = parent::morphMany($related, $name, $type, $id, $localKey);
        }

        return $relationship;
    }

    /**
     * Define a many-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $table
     * @param  string  $foreignKey
     * @param  string  $otherKey
     * @param  string  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function belongsToMany($related, $table = null, $foreignKey = null, $otherKey = null, $relation = null)
    {
        if (is_array($related)) {
            $relationship = $this->createMetaRelation();

            foreach ($related as $relative) {
                $relationship->merge(parent::belongsToMany($relative, $table, $foreignKey, $otherKey, $relation));
            }
        } else {
            $relationship = parent::belongsToMany($related, $table, $foreignKey, $otherKey, $relation);
        }

        return $relationship;
    }

    /**
     * Define a polymorphic many-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $name
     * @param  string  $table
     * @param  string  $foreignKey
     * @param  string  $otherKey
     * @param  bool  $inverse
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function morphToMany($related, $name, $table = null, $foreignKey = null, $otherKey = null, $inverse = false)
    {
        if (is_array($related)) {
            $relationship = $this->createMetaRelation();

            foreach ($related as $relative) {
                $relationship->merge(parent::morphToMany($relative, $name, $table, $foreignKey, $otherKey, $inverse));
            }
        } else {
            $relationship = parent::morphToMany($related, $name, $table, $foreignKey, $otherKey, $inverse);
        }

        return $relationship;
    }
}
