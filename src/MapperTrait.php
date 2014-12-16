<?php

namespace Echidna;

trait MapperTrait
{
    /** @var MapperInterface */
    private $mapper;

    public function setMapper(MapperInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    public function getMapper()
    {
        return $this->mapper;
    }
} 