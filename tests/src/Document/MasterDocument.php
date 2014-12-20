<?php

namespace EchidnaTest\Document;

use Echidna\Document;
use Echidna\Reference;

class MasterDocument extends Document
{
    protected static $collection = 'master';

    public static function fields()
    {
        $fields['_id']  = ['type' => 'id', 'default' => new \MongoId()];
        $fields['name'] = ['type' => 'string'];

        return $fields;
    }

    public static function references()
    {
        return [
            'detail' => ['type' => Reference::HAS_MANY, 'local_field' => '_id', 'foreign_document' => "EchidnaTest\\Document\\DetailDocument", 'foreign_field' => 'master_id']
        ];
    }
} 