<?php

namespace Echidna;

class Cursor implements CursorInterface
{

    use MapperTrait;

    /** @var \MongoCursor */
    private $cursor;

    /**
     * @param \MongoCursor $cursor
     * @param MapperInterface $mapper
     */
    public function __construct(\MongoCursor $cursor, MapperInterface $mapper = null)
    {
        $this->setCursor($cursor);

        if ($mapper !== null) $this->setMapper($mapper);
    }

    /**
     * @param \MongoCursor $cursor
     *
     * @return $this
     */
    public function setCursor(\MongoCursor $cursor)
    {
        $this->cursor = $cursor;

        return $this;
    }

    /**
     * @return \MongoCursor
     */
    public function getCursor()
    {
        return $this->cursor;
    }

    /**
     * @param string|array $reference
     */
    public function with($reference)
    {
        $documents  = $this->getData();
        $database   = $this->getDatabase();
        $references = $this->normalizeReference($reference);

        if (
            empty($documents)
            || empty($database)
            || empty($references)
        ) return null;

        $document_instance   = current($documents);
        $document_references = $document_instance->getReferences();

        $result = [];
        foreach ($references as $offset) {
            if (!isset($document_references[$offset])) continue;

            $ref    = $document_references[$offset];
            $values = [];
            foreach ($documents as $document) {
                $values[] = $document->getFilteredValue($ref['local_field'], 'mongo');
            }

            $items = Echidna::lookupReference($database, $ref, $values);

            foreach ($items as $item) {
                $master_id = $item[$ref['foreign_field']];
                $item_id   = (string) $item['_id'];

                if ($ref['type'] == Reference::HAS_ONE) {
                    $result[$master_id][$offset] = $item;
                } else {
                    $result[$master_id][$offset][$item_id] = $item;
                }
            }
        }

        foreach ($result as $id => $item) {
            foreach ($item as $offset => $value) {
                $documents[$id][$offset] = $value;
            }
        }

        unset($result);

        return $documents;
    }

    private function normalizeReference($reference)
    {
        if (is_string($reference)) $reference = [$reference];
        if (!is_array($reference)) return null;

        $reference = array_map('trim', $reference);
        $reference = array_filter($reference, function ($value) {
            return !empty($value);
        });

        if (empty($reference)) return null;

        return $reference;
    }

    /**
     * @return mixed|null
     */
    public function current()
    {
        $result = $this->getCursor()->current();
        $mapper = $this->getMapper();

        return
            ($mapper && $result)
                ? Echidna::document($mapper->getDocument(), $result, false, $mapper, ['after_get'])
                : null;
    }

    /**
     * @return null|void
     */
    public function next()
    {
        $result = $this->getCursor()->next();
        $mapper = $this->getMapper();

        return
            ($mapper && $result)
                ? Echidna::document($mapper->getDocument(), $result, false, $mapper, ['after_get'])
                : null;
    }

    /**
     * @return string
     */
    final public function key()
    {
        return $this->getCursor()->key();
    }

    /**
     * @return bool
     */
    final public function valid()
    {
        return $this->getCursor()->valid();
    }

    final public function rewind()
    {
        return $this->getCursor()->rewind();
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->getCursor()->count(false);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return iterator_to_array($this);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data = $this->getData();
        foreach ($data as $key => $value) {
            if ($value instanceof ResultInterface) {
                $data[$key] = $value->toArray();
            }
        }

        return $data;
    }
}