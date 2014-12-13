<?php

namespace Echidna;

interface TypeInterface
{

    public function getPHPValue($value);

    public function getMongoValue($value);

} 