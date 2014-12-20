<?php

namespace Echidna\Type;

use DataEntity\Type;
use Echidna\Echidna;

class DocumentType extends Type
{

    public function validate($value)
    {
        return null !== Echidna::document_class($value);
    }

    public function filter($value, $context = null)
    {
        return Echidna::document_class($value);
    }
} 