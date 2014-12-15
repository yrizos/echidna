<?php

namespace Echidna\Type;

use DataObject\Type\UrlType as ParentType;
use Echidna\TypeInterface;

class UrlType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 