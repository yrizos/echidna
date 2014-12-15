<?php

namespace Echidna;

abstract class Type extends \DataObject\Type implements TypeInterface
{
    abstract function filterMongo($value);

    public static function factory($type)
    {
        if (strpos($type, "\\") === false) {
            $type = ucfirst(trim(strval($type)));
            if (strripos(strrev($type), strrev('type')) === false) $type .= 'Type';

            $type = "Echidna\\Type\\" . $type;
        }

        if (
            !class_exists($type)
            || !in_array("Echidna\\TypeInterface", class_implements($type))
        ) throw new \InvalidArgumentException('Type ' . $type . ' is invalid.');

        return new $type;
    }
}
