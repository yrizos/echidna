<?php

namespace Echidna;

abstract class Type implements TypeInterface
{

    abstract function getPHPValue($value);

    abstract function getMongoValue($value);

} 