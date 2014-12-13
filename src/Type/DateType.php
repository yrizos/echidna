<?php

namespace Echidna\Type;

use Echidna\Type;

class DateType extends Type
{

    public function getPHPValue($value)
    {
        if ($value === null) return null;

        if (is_string($value)) {
            $value = new \DateTime($value);
        } else if (is_numeric($value)) {
            $value = new \DateTime('@' . $value);
        } else if ($value instanceof \MongoDate) {
            $value = new \DateTime('@' . $value->sec);
        }

        return ($value instanceof \DateTime) ? $value : null;
    }

    public function getMongoValue($value)
    {
        if ($value === null) return null;

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