<?php

namespace EchidnaTest\Document;

use Echidna\Document;

class DepartmentDocument extends Document
{

    protected static $collection = 'department';

    public static function fields()
    {
        $fields         = parent::fields();
        $fields['name'] = ['type' => 'string', 'default' => null];

        return $fields;
    }

}