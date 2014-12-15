<?php

namespace Echidna;

class Echidna
{
    /**
     * @param $document
     * @param array $data
     * @param bool $isNew
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public static function document($document, array $data = [], $isNew = true)
    {
        if (!($document instanceof DocumentInterface)) {
            if (
                !class_exists($document)
                || !in_array("Echidna\\DocumentInterface", class_implements($document))
            ) throw new \InvalidArgumentException('Document is invalid.');

            $document = new $document;
        }

        $document->setData($data)->setNew($isNew);

        return $document;
    }

    /**
     * @param $type
     * @return TypeInterface
     */
    public static function type($type)
    {
        return Type::factory($type);
    }

    /**
     * @param \MongoDB $database
     * @param $document
     * @return MapperInterface
     * @throws \InvalidArgumentException
     */
    public static function mapper(\MongoDB $database, $document)
    {
        if (
            !class_exists($document)
            || !in_array("Echidna\\DocumentInterface", class_implements($document))
        ) throw new \InvalidArgumentException('Document is invalid.');

        $mapper = $document::mapper();

        return new $mapper($database, $document);
    }

} 