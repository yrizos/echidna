<?php

namespace Echidna;

use DataObject\DataObject;

class Document extends DataObject implements DocumentInterface
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