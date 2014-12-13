<?php

namespace Echidna;

use DataObject\DataObjectInterface;

interface EntityInterface extends DataObjectInterface, ResultInterface
{

    public static function collection($collection = null);

}