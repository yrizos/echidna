<?php

namespace Echidna\Type;


use DataEntity\Type;

class IdType extends Type
{

    public function validate($value)
    {
        if (is_string($value)) {
            try {
                $value = new \MongoId($value);
            } catch (\MongoException $e) {
                return false;
            }
        }

        return ($value instanceof \MongoId);
    }

    public function filter($value, $context = null)
    {
        if (is_string($value)) {
            try {
                $value = new \MongoId($value);
            } catch (\MongoException $e) {
                return null;
            }
        }

        if (!($value instanceof \MongoId)) return null;

        return
            $context === 'mongo'
                ? $value
                : (string)$value;
    }
} 