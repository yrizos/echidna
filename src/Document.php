<?php
namespace Echidna;

use DataEntity\Entity;
use Sabre\Event\EventEmitterInterface;

class Document extends Entity implements DocumentInterface
{

    use MapperTrait;

    /** @var string */
    protected static $collection;

    /** @var string */
    protected static $mapper_class = "Echidna\\Mapper";

    /** @var bool */
    private $new = true;

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->new === true;
    }

    /**
     * @param bool $new
     *
     * @return $this
     */
    public function setNew($new)
    {
        $this->new = $new === true;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            if ($value instanceof DocumentInterface) $value = $value->toArray();

            return $value;
        }, $this->getData());
    }

    /**
     * @param string|\DataEntity\TypeInterface $type
     *
     * @return \DataEntity\TypeInterface
     */
    protected function getType($type)
    {
        return Echidna::type($type);
    }

    /**
     * @return string
     * @throws \LogicException
     */
    public static function collection()
    {
        if (null === static::$collection) throw new \LogicException("I don't know my collection.");

        return static::$collection;
    }

    /**
     * @return string
     */
    public static function mapper()
    {
        if (!(class_implements(static::$mapper_class, "Echidna\\MapperInterface"))) static::$mapper_class = "Echidna\\Mapper";

        return static::$mapper_class;
    }

    /**
     * @return array
     */
    public static function fields()
    {
        return [];
    }

    /**
     * return array
     */
    public static function references()
    {

    }

    /**
     * @param EventEmitterInterface $emitter
     */
    public static function events(EventEmitterInterface $emitter)
    {

    }

}