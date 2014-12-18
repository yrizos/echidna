<?php
namespace Echidna;

trait MapperTrait
{

    /** @var  MapperInterface */
    private $mapper;

    final public function setMapper(MapperInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    final public function getMapper()
    {
        return $this->mapper;
    }
} 