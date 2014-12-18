<?php

namespace EchidnaTest\Document;

class ComplexDocument extends SimpleDocument
{

    public static function fields()
    {
        $fields = parent::fields();

        $fields['integer']         = ['type' => 'integer'];
        $fields['integer_default'] = ['type' => 'integer', 'default' => 1];
        $fields['bool']            = ['type' => 'bool'];
        $fields['bool_default']    = ['type' => 'bool', 'default' => true];
        $fields['string']          = ['type' => 'string'];
        $fields['string_default']  = ['type' => 'string', 'default' => 'hello world'];
        $fields['email']           = ['type' => 'email'];
        $fields['email_default']   = ['type' => 'email', 'default' => 'username@example.com'];
        $fields['float']           = ['type' => 'float'];
        $fields['float_default']   = ['type' => 'float', 'default' => 3.14];
        $fields['ip']              = ['type' => 'ip'];
        $fields['ip_default']      = ['type' => 'ip', 'default' => '127.0.0.1'];
        $fields['url']             = ['type' => 'url'];
        $fields['url_default']     = ['type' => 'url', 'default' => 'example.com'];

        return $fields;
    }
} 