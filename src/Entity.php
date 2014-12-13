<?php

namespace Echidna;

use DataObject\DataObject;

class Entity extends DataObject implements EntityInterface
{

    private $new = true;
    private $modified = false;

    /** @var  string */
    protected static $collection;

    public static function collection($collection = null)
    {
        if (null !== $collection) {
            static::$collection = $collection;
        }

        return static::$collection;
    }

    public function toArray()
    {
        return $this->getData();
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

} 