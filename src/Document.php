<?php
namespace Echidna;

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

    /** @var array */
    private $references;

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $reference = $this->getReference($offset);

        if ($reference) {
            if (null !== $reference['value']) return $reference['value'];

            $database    = $this->getDatabase();
            $local_value = $this[$reference['local_field']];

            if (!(null === $database || null === $local_value)) {
                $reference['value']        = Echidna::lookupReference($database, $reference, $local_value);
                $this->references[$offset] = $reference;
            }

            return $reference['value'];
        }

        return parent::offsetGet($offset);
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $reference = $this->getReference($offset);

        if ($reference) {
            $reference['value']        = $value;
            $this->references[$offset] = $reference;
        } else {
            parent::offsetSet($offset, $value);
        }
    }

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
    public function getReferences()
    {
        if (null === $this->references) {
            $this->references = [];
            $definition       = is_array(static::references()) ? static::references() : [];

            foreach ($definition as $name => $reference) {
                $name = trim(strval($name));
                if (empty($name)) continue;

                if (is_array($reference)) {
                    $type             = isset($reference['type']) ? $reference['type'] : Reference::HAS_ONE;
                    $local_document   = $this;
                    $local_field      = isset($reference['local_field']) ? $reference['local_field'] : null;
                    $foreign_document = isset($reference['foreign_document']) ? $reference['foreign_document'] : null;
                    $foreign_field    = isset($reference['foreign_field']) ? $reference['foreign_field'] : null;
                    $reference        = new Reference($type, $local_document, $local_field, $foreign_document, $foreign_field);
                }

                if ($reference instanceof ReferenceInterface) {
                    $this->references[$name] = $reference;
                }
            }
        }

        return $this->references;
    }

    /**
     * @param string $offset
     * @return bool
     */
    private function getReference($offset)
    {
        return isset($this->getReferences()[$offset]) ? $this->getReferences()[$offset] : null;
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
        return [];
    }

    /**
     * @param EventEmitterInterface $emitter
     */
    public static function events(EventEmitterInterface $emitter)
    {

    }

}