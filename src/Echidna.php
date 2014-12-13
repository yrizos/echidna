<?php

namespace Echidna;

class Echidna
{

    public static function buildDocument($document, array $data = [])
    {
        if (!($document instanceof DocumentInterface)) {
            if (!class_exists($document) || !in_array("Echidna\\DocumentInterface", class_implements($document))) throw new \InvalidArgumentException();

            $document = new $document;
        }

        $document->setData($data);

        return $document;
    }

} 