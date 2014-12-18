<?php

namespace EchidnaTest\Document;

use Echidna\DocumentInterface;
use Sabre\Event\EventEmitterInterface;

class EventfulDocument extends SimpleDocument
{

    public static function fields()
    {
        $fields = parent::fields();

        $fields['before_save'] = ['type' => 'bool', 'default' => false];
        $fields['after_save']  = ['type' => 'bool', 'default' => false];
        $fields['after_get']   = ['type' => 'bool', 'default' => false];

        return $fields;
    }

    public static function events(EventEmitterInterface $eventEmitter)
    {
        $eventEmitter->on('before_save', function (DocumentInterface $document) {
            $document->before_save = true;
        });

        $eventEmitter->on('after_save', function (DocumentInterface $document) {
            $document->after_save = true;
        });

        $eventEmitter->on('after_get', function (DocumentInterface $document) {
            $document->after_get = true;
        });
    }

} 