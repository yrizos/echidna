<?php

namespace EchidnaTest\Document;

use Echidna\Document;

class SimpleDocument extends Document
{

    protected static $collection = 'test';
    protected static $mapper_class = "EchidnaTest\\Document\\TestMapper";

    public static function fields()
    {

        $fields['_id']    = ['type' => 'id', 'default' => new \MongoId()];
        $fields['string'] = ['type' => 'string'];

        return $fields;
    }
} 