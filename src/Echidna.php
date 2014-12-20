<?php

namespace Echidna;

use DataEntity\Type;
use DataEntity\TypeInterface;

class Echidna
{

    /**
     * @param string $document
     *
     * @return null|string
     */
    public static function document_class($document)
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
     * @param \MongoDB $database
     * @param          $document
     *
     * @return MapperInterface
     * @throws \InvalidArgumentException
     */
    public static function mapper(\MongoDB $database, $document)
    {
        if (!self::document_class($document)) throw new \InvalidArgumentException('Document is invalid.');

        $mapper = $document::mapper();

        return new $mapper($database, $document);
    }

    /**
     * @param $type
     *
     * @return TypeInterface
     */
    public static function type($type)
    {
        if ($type instanceof TypeInterface) return $type;

        $class = '';
        if (strpos($type, "\\") === false) {
            $class = ucfirst(trim(strval($type)));
            if (strripos(strrev($class), strrev('type')) === false) $class .= 'Type';

            $class = "Echidna\\Type\\" . $class;
        }

        if (
            class_exists($class)
            && in_array("DataEntity\\TypeInterface", class_implements($class))
        ) return new $class;

        return Type::factory($type);
    }

    /**
     * @param DocumentInterface|string $document
     * @param array $data
     * @param bool $isNew
     * @param MapperInterface $mapper
     * @param array $events
     * @return DocumentInterface
     * @throws \InvalidArgumentException
     */
    public static function document($document, array $data = null, $isNew = true, MapperInterface $mapper = null, array $events = [])
    {
        if (!($document instanceof DocumentInterface)) {
            if (!self::document_class($document)) throw new \InvalidArgumentException('Document is invalid.');

            $document = new $document;

            if (null !== $data) $document->setData($data);
        } else {
            if (null !== $data) {
                foreach ($data as $offset => $value) {
                    $document[$offset] = $value;
                }
            }
        }

        $document->setNew($isNew);

        if (null !== $mapper) {
            $document->setMapper($mapper);
            $document::events($mapper->getEventEmitter());

            if (!empty($events)) $mapper->emit($document, $events);
        }

        return $document;
    }

    public static function lookupReference(\MongoDB $database, ReferenceInterface $reference, $local_value)
    {
        $foreign_mapper = $reference['foreign_document']::mapper();
        $foreign_mapper = new $foreign_mapper($database, $reference['foreign_document']);
        $function       = $reference['type'] == Reference::HAS_ONE ? 'findOne' : 'find';
        $lookup         = is_array($local_value) ? [$reference['foreign_field'] => ['$in' => $local_value]] : [$reference['foreign_field'] => $local_value];
        $result         = $foreign_mapper->$function($lookup);

        if ($result instanceof CursorInterface) $result = $result->getData();

        return $result;
    }

} 