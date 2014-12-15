<?php

namespace Echidna\Type;

use DataObject\Type\FloatType as ParentType;
use Echidna\TypeInterface;

class FloatType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 