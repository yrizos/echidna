<?php

namespace Echidna\Type;

class IdType extends StringType
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

    public function filter($value)
    {
        if ($value instanceof \MongoId) {
            $value = (string) $value;
            $value = parent::filter($value);
        }

        return !empty($value) ? $value : null;
    }

    public function filterMongo($value)
    {
        if (is_string($value)) {
            try {
                $value = new \MongoId($value);
            } catch (\MongoException $e) {
                $value = new \MongoId();
            }
        }

        return ($value instanceof \MongoId) ? $value : null;
    }
}