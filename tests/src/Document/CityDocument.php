<?php

namespace EchidnaTest\Document;

use Echidna\Document;

class CityDocument extends Document
{

    protected static $collection = 'city';

    public static function fields()
    {
        $fields         = parent::fields();
        $fields['name'] = ['type' => 'string', 'default' => null];

        return $fields;
    }

}