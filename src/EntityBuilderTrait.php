<?php

namespace Echidna;

trait EntityBuilderTrait
{

    /** @var string */
    private $entity;

    protected function setEntity($entity)
    {
        if (!class_exists($entity) || !in_array("Echidna\\EntityInterface", class_implements($entity))) throw new \InvalidArgumentException();

        $collection = $entity::collection();

        if (empty($collection)) throw new \InvalidArgumentException();

        $this->entity = $entity;

        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function build(array $data = [])
    {
        return EntityBuilder::build($this->getEntity(), $data);
    }

} 