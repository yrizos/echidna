<?php
namespace Echidna;

class Reference implements ReferenceInterface
{

    /** @var string */
    private $name;

    /** @var mixed */
    private $type;

    /** @var string */
    private $local_collection;

    /** @var string */
    private $local_field;

    /** @var string */
    private $foreign_collection;

    /** @var string */
    private $foreign_field;

    public function __construct($name, $type, $local_collection, $local_field, $foreign_collection, $foreign_field)
    {

    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getLocalCollection()
    {
        return $this->local_collection;
    }

    public function getLocalField()
    {
        return $this->getLocalField();
    }

    public function getForeignCollection()
    {
        return $this->foreign_collection;
    }

    public function getForeignCollectionField()
    {
        return $this->foreign_collection;
    }

} 