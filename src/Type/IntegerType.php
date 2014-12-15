<?php

namespace Echidna\Type;

use DataObject\Type\IntegerType as ParentType;
use Echidna\TypeInterface;

class IntegerType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 