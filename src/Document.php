<?php

namespace Echidna;

use DataObject\Entity;

class Document extends Entity implements DocumentInterface
{

    use MapperTrait;

    /** @var  string */
    protected static $collection = "echidna_document";

    private $new = true;

    /** @var array */
    private $refs = null;

    /** @var MapperInterface */
    private $builder = null;

    /**
     * Mapper class
     *
     * @var string
     */
    protected static $mapper_class = "Echidna\\Mapper";

    public function getReferences()
    {
        if (null === $this->refs) {
            $definition = is_array(static::references()) ? static::references() : [];

            foreach ($definition as $offset => $value) {
                $offset              = trim(strval($offset));
                $this->refs[$offset] = Echidna::reference($this, $offset, $value);
            }
        }

        return $this->refs;
    }

    public function offsetGet($offset)
    {
        $value = parent::offsetGet($offset);

        if ($value) return $value;

        $mapper = $this->getMapper();
        $ref    = Echidna::buildReference($this, $offset);

        if ($ref['field'] && $ref['document'] && $mapper) {
            $mapper = Echidna::buildMapper($mapper->getDatabase(), $ref['document']);

            try {
                $value = $mapper->get($this[$ref['field']]);
            } catch (\Exception $e) {
                $value = null;
            }

            parent::offsetSet($offset, $value);

            return $value;
        }

        return null;
    }


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
     *
     * @return TypeInterface
     */
    protected function getType($type)
    {
        return Echidna::buildType($type);
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
        return parent::getData();
    }

    public static function mapper()
    {
        if (!(class_implements(static::$mapper_class, "Echidna\\MapperInterface"))) static::$mapper_class = "Echidna\\Mapper";

        return static::$mapper_class;
    }

    public static function collection()
    {
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

    public static function references()
    {

    }

    public static function events(EventEmitterInterface $eventEmitter)
    {

    }

} 