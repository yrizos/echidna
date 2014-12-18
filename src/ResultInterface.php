<?php

namespace Echidna;

interface ResultInterface
{

    public function setMapper(MapperInterface $mapper);

    public function getMapper();

    public function toArray();
} 