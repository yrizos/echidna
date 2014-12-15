<?php

namespace Echidna\Type;

use DataObject\Type\RawType as ParentType;
use Echidna\TypeInterface;

class RawType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 