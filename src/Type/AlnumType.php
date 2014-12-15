<?php

namespace Echidna\Type;

use DataObject\Type\AlnumType as ParentType;
use Echidna\TypeInterface;

class AlnumType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 