<?php

namespace Echidna\Type;

use DataObject\Type\DateType as ParentType;
use Echidna\TypeInterface;

class DateType extends ParentType implements TypeInterface
{

    public function validate($value)
    {
        return ($value instanceof \MongoDate) || parent::validate($value);
    }

    public function filter($value)
    {
        if ($value instanceof \MongoDate) {
            $value = new \DateTime('@' . $value->sec);
        }

        $value = parent::filter($value);

        return $value ? $value : null;
    }

    public function filterMongo($value)
    {
        if (is_string($value)) {
            $value = new \MongoDate(strtotime($value));
        } else if (is_numeric($value)) {
            $value = new \MongoDate($value);
        } else if ($value instanceof \DateTime) {
            $value = new \MongoDate($value->format('U'));
        }

        return ($value instanceof \MongoDate) ? $value : null;
    }
}