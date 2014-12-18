<?php
namespace Echidna;

use DataEntity\EntityInterface;
use Sabre\Event\EventEmitterInterface;

interface DocumentInterface extends EntityInterface, ResultInterface
{

    public function isNew();

    public function setNew($new);

    public static function collection();

    public static function mapper();

    public static function fields();

    public static function references();

    public static function events(EventEmitterInterface $emitter);
} 