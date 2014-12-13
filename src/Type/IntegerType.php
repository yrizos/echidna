<?php

namespace Echidna\Type;

use Echidna\Type;

class IntegerType extends Type
{

    public function getPHPValue($value)
    {
        return
            $value !== null
                ? (int) $value
                : null;
    }

    public function getMongoValue($value)
    {
        return $this->getPHPValue($value);
    }
} 