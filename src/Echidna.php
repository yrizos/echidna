<?php

namespace Echidna;

class Echidna
{

    /**
     * @param DocumentInterface|string $document
     * @param array                    $data
     * @param bool                     $isNew
     * @param MapperInterface          $mapper
     * @param array                    $events
     *
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public static function buildDocument($document, array $data = null, $isNew = null, MapperInterface $mapper = null, array $events = [])
    {
        if (!($document instanceof DocumentInterface)) {
            if (!self::getDocumentClass($document)) throw new \InvalidArgumentException('Document is invalid.');

            $document = new $document;

            if (null !== $data) $document->setData($data);
        } else {
            if (null !== $data) {
                foreach ($data as $offset => $value) {
                    $document[$offset] = $value;
                }
            }
        }

        if (null !== $isNew) $document->setNew($isNew);
        if (null !== $mapper) {
            $document->setMapper($mapper);
            $document::events($mapper->getEventEmitter());

            if (!empty($events)) $mapper->emit($document, $events);
        }

        return $document;
    }

    public static function buildReference($master, $offset, $ref = null)
    {
        $offset = trim(strval($offset));
        $master = self::getDocumentClass($master);

        if (empty($offset) || !$master) return null;

        if (empty($ref)) {
            $refs = $master::references();
            $ref  = is_array($refs) && isset($refs[$offset]) ? $refs[$offset] : null;
        }

        $field  = is_array($ref) && isset($ref['field']) ? $ref['field'] : null;
        $detail = is_array($ref) && isset($ref['document']) ? self::getDocumentClass($ref['document']) : null;

        if (!$field || !$detail) return null;

        $fields = $master::fields();
        $fields = is_array($fields) ? array_keys($fields) : [];

        if (!in_array($field, $fields)) $field = null;

        return [
            'document' => $detail,
            'field'    => $field
        ];
    }


    /**
     * @param DocumentInterface|string $document
     *
     * @return null|string
     */
    public static function getDocumentClass($document)
    {
        if ($document instanceof DocumentInterface) return get_class($document);

        return
            is_string($document)
            && class_exists($document)
            && in_array("Echidna\\DocumentInterface", class_implements($document))
                ? $document
                : null;
    }

    /**
     * @param $type
     *
     * @return TypeInterface
     */
    public static function buildType($type)
    {
        return Type::factory($type);
    }

    /**
     * @param \MongoDB $database
     * @param          $document
     *
     * @return MapperInterface
     * @throws \InvalidArgumentException
     */
    public static function buildMapper(\MongoDB $database, $document)
    {
        if (!self::getDocumentClass($document)) throw new \InvalidArgumentException('Document is invalid.');

        $mapper = $document::mapper();

        return new $mapper($database, $document);
    }
}