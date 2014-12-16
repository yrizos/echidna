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
            if (!self::document_class($document)) throw new \InvalidArgumentException('Document is invalid.');

            $document = new $document;
        }

        $document->setData($data)->setNew($isNew);

        return $document;
    }

    public static function document_class($document)
    {
        if ($document instanceof DocumentInterface) return get_class($document);

        return
            class_exists($document)
            && in_array("Echidna\\DocumentInterface", class_implements($document))
                ? $document
                : null;
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
        if (!self::document_class($document)) throw new \InvalidArgumentException('Document is invalid.');

        $mapper = $document::mapper();

        return new $mapper($database, $document);
    }

    public static function reference($master, $offset, $ref = null)
    {
        $offset = trim(strval($offset));
        $master = self::document_class($master);

        if (empty($offset) || !$master) return null;

        if (empty($ref)) {
            $refs = $master::references();
            $ref  = is_array($refs) && isset($refs[$offset]) ? $refs[$offset] : null;
        }

        $field  = is_array($ref) && isset($ref['field']) ? $ref['field'] : null;
        $detail = is_array($ref) && isset($ref['document']) ? self::document_class($ref['document']) : null;

        if (!$field || !$detail) return null;

        $fields = $master::fields();
        $fields = is_array($fields) ? array_keys($fields) : [];

        if (!in_array($field, $fields)) return null;

        return [
            'document' => $detail,
            'field'    => $field
        ];
    }
} 