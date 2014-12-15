<?php

namespace Echidna\Type;

use DataObject\Type\IpType as ParentType;
use Echidna\TypeInterface;

class IpType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 