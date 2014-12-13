<?php

namespace Echidna\Type;

use Echidna\Type;

class FloatType extends Type
{

    public function getPHPValue($value)
    {
        return
            $value !== null
                ? (float) $value
                : null;
    }

    public function getMongoValue($value)
    {
        return $this->getPHPValue($value);
    }
} 