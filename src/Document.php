<?php

namespace Echidna;

use DataObject\Entity;

class Document extends Entity implements DocumentInterface
{
    /** @var  string */
    protected static $collection = "echidna_document";

    private $new = true;

    /** @var array */
    private $references = null;

    /** @var MapperInterface */
    private $builder = null;

    /**
     * Mapper class
     *
     * @var string
     */
    protected static $mapper = "Echidna\\Mapper";

    public function setBuilder(MapperInterface $buidler)
    {
        $this->builder = $buidler;

        return $this;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function getReferences()
    {
        if (null === $this->references) {
            $definition = is_array(static::references()) ? static::references() : [];

            foreach ($definition as $offset => $value) {
                $offset = trim(strval($offset));
                if (empty($offset)) continue;

                $field    = isset($value['field']) ? trim(strval($value['field'])) : null;
                $document = isset($value['document']) ? $value['document'] : null;

                if (
                    !$field
                    || !$document
                    || !$this->getField($field)
                    || !class_exists($document)
                    || !in_array("Echidna\\DocumentInterface", class_implements($document))
                ) continue;

                $this->references[$offset] = [
                    'field'    => $field,
                    'document' => $document,
                ];
            }
        }

        return $this->references;
    }

    public function offsetGet($offset)
    {
        $value = parent::offsetGet($offset);
        if ($value) return $value;

        $builder = $this->getBuilder();
        $ref     = @$this->getReferences()[$offset];
        $id      = $ref ? $this[$ref['field']] : null;

        if ($id && $builder) {
            $mapper = Echidna::mapper($builder->getDatabase(), $ref['document']);

            try {
                $value = $mapper->get($id);
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
     * @return TypeInterface
     */
    protected function getType($type)
    {
        return Echidna::type($type);
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

    public static function mapper()
    {
        if (!(class_implements(static::$mapper, "Echidna\\MapperInterface"))) static::$mapper = "Echidna\\Mapper";

        return static::$mapper;
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