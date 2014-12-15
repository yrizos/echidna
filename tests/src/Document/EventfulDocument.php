<?php

namespace EchidnaTest\Document;

use Echidna\Document;
use Echidna\DocumentInterface;
use Echidna\EventEmitterInterface;

class EventfulDocument extends Document
{

    public static function fields()
    {
        $fields                = parent::fields();
        $fields['index']       = ['type' => 'string', 'default' => null];
        $fields['before_save'] = ['type' => 'string', 'default' => 'no'];
        $fields['after_save']  = ['type' => 'string', 'default' => 'no'];
        $fields['after_get']   = ['type' => 'string', 'default' => 'no'];

        return $fields;
    }

    public static function events(EventEmitterInterface $eventEmitter)
    {
        $eventEmitter->on('before_save', function (DocumentInterface $document) {
            $document->before_save = 'yes';
        });

        $eventEmitter->on('after_save', function (DocumentInterface $document) {
            $document->after_save = 'yes';
        });

        $eventEmitter->on('after_get', function (DocumentInterface $document) {
            $document->after_get = 'yes';
        });
    }
} 