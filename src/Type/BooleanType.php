<?php

namespace Echidna\Type;

use DataObject\Type\BooleanType as ParentType;
use Echidna\TypeInterface;

class BooleanType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 