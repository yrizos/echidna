<?php

namespace Echidna\Type;

use DataEntity\Type\DateType as DataEntityDateType;

class DateType extends DataEntityDateType
{

    public function validate($value)
    {
        return
            ($value instanceof \MongoDate)
            || parent::validate($value);
    }

    public function filter($value, $context = null)
    {
        $value = parent::filter($value);

        return
            ($context === 'mongo' && $value)
                ? new \MongoDate($value->getTimestamp())
                : $value;
    }
} 