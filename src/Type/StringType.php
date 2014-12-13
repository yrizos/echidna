<?php

namespace Echidna\Type;

use Echidna\Type;

class StringType extends Type
{

    public function getPHPValue($value)
    {
        return
            $value !== null
                ? (string) $value
                : null;
    }

    public function getMongoValue($value)
    {
        return $this->getPHPValue($value);
    }
} 