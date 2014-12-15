<?php

namespace Echidna;

interface TypeInterface extends \DataObject\TypeInterface
{
    public function filterMongo($value);
}