<?php

namespace Echidna\Type;

use DataObject\Type\StringType as ParentType;
use Echidna\TypeInterface;

class StringType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 