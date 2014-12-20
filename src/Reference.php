<?php
namespace Echidna;

class Reference extends Entity implements ReferenceInterface
{

    const HAS_ONE  = 1;
    const HAS_MANY = 2;

    public function __construct($type, $local_document, $local_field, $foreign_document, $foreign_field)
    {
        $this['type']             = $type;
        $this['local_document']   = $local_document;
        $this['local_field']      = $local_field;
        $this['foreign_document'] = $foreign_document;
        $this['foreign_field']    = $foreign_field;
    }

    public static function fields()
    {
        return [
            'type'             => ['type' => 'integer'],
            'local_document'   => ['type' => 'document'],
            'local_field'      => ['type' => 'string'],
            'foreign_document' => ['type' => 'document'],
            'foreign_field'    => ['type' => 'string'],
            'value'            => ['type' => 'raw'],
        ];
    }

} 