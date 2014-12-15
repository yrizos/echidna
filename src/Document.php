<?php

namespace Echidna;

use DataObject\Entity;

class Document extends Entity implements DocumentInterface
{

    private $new = true;

    /** @var  string */
    protected static $collection;

    public function setNew($new)
    {
        $this->new = $new === true;

        return $this;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->new === true;
    }

    /**
     * @param $type
     * @return TypeInterface
     */
    protected function getType($type)
    {
        return Type::factory($type);
    }

    /**
     * @return array
     */
    public function getMongoData()
    {
        $data  = $this->getRawData();
        $array = [];
        foreach ($data as $offset => $value) {
            if (!$value) $value = $this->getDefault($offset);

            $type  = $this->getFieldType($offset);
            $value = $type->filterMongo($value);

            $array[$offset] = $value;
        }

        return $array;
    }

    public function toArray()
    {
        return $this->getData();
    }

    public static function collection($collection = null)
    {
        if (null !== $collection) static::$collection = $collection;

        return static::$collection;
    }

    public static function fields()
    {
        return [
            '_id'         => ['type' => 'id', 'default' => new \MongoId()],
            'date_create' => ['type' => 'date', 'default' => new \DateTime()],
            'date_update' => ['type' => 'date', 'default' => null],
        ];
    }

} 