<?php

namespace Echidna;

use DataObject\EntityInterface;

interface DocumentInterface extends EntityInterface
{
    public function setNew($new);

    public function isNew();

    public function getMongoData();

    public function toArray();

    public static function mapper();

    public static function collection();

    public static function fields();

    public static function events(EventEmitterInterface $eventEmmiter);
}