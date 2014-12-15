<?php

namespace Echidna\Type;

use DataObject\Type\EmailType as ParentType;
use Echidna\TypeInterface;

class EmailType extends ParentType implements TypeInterface
{

    public function filterMongo($value)
    {
        return $this->filter($value);
    }

} 