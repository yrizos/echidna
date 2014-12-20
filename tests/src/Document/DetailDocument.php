<?php

namespace EchidnaTest\Document;

use Echidna\Document;
use Echidna\Reference;

class DetailDocument extends Document
{
    protected static $collection = 'detail';

    public static function fields()
    {
        $fields['_id']       = ['type' => 'id', 'default' => new \MongoId()];
        $fields['name']      = ['type' => 'string'];
        $fields['master_id'] = ['type' => 'id'];

        return $fields;
    }

    public static function references()
    {
        return [
            'master' => ['type' => Reference::HAS_ONE, 'local_field' => 'master_id', 'foreign_document' => "EchidnaTest\\Document\\MasterDocument", 'foreign_field' => '_id']
        ];
    }
} 