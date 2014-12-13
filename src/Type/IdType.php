<?php

namespace Echidna\Type;

use Echidna\Type;

class IdType extends Type
{

    public function getPHPValue($value)
    {
        if (
            $value instanceof \MongoId
            || is_scalar($value)
        ) $value = (string) $value;

        return is_string($value) ? $value : null;
    }

    public function getMongoValue($value)
    {
        if ($value === null) return null;

        if (!($value instanceof \MongoId)) {
            try {
                $value = new \MongoId($value);
            } catch (\MongoException $e) {
                return null;
            }
        }

        return $value;
    }
} 