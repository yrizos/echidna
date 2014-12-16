<?php

namespace EchidnaTest\Document;

use Echidna\Document;

class UserDocument extends Document
{
    protected static $collection = 'user';

    public static function fields()
    {
        $fields = parent::fields();

        $fields['city_id'] = ['type' => 'id', 'default' => null];
        $fields['username'] = ['type' => 'string'];
        $fields['password'] = ['type' => 'string'];
        $fields['email']    = ['type' => 'email'];

        return $fields;
    }

    public static function references()
    {
        return [
            'city' => ['field' => 'city_id', 'document' => "EchidnaTest\\Document\\CityDocument"]
        ];
    }
} 