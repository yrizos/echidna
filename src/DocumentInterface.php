<?php

namespace Echidna;

use DataObject\EntityInterface;

interface DocumentInterface extends EntityInterface
{

    public function setBuilder(MapperInterface $buidler);

    public function getBuilder();

    public function getReferences();

    public function setNew($new);

    public function isNew();

    public function getMongoData();

    public function toArray();

    public static function mapper();

    public static function collection();

    public static function fields();

    public static function references();

    public static function events(EventEmitterInterface $eventEmmiter);
}