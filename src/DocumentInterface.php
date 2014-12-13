<?php

namespace Echidna;

use DataObject\DataObjectInterface;

interface DocumentInterface extends DataObjectInterface, ResultInterface
{

    public static function collection($collection = null);

}