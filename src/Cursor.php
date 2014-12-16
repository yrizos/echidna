<?php

namespace Echidna;

use Echidna\Type\IdType;

class Cursor implements CursorInterface
{

    use MapperTrait;

    /** @var \MongoCursor */
    private $cursor;

    public function __construct(\MongoCursor $cursor, MapperInterface $mapper = null)
    {
        $this->setCursor($cursor);

        if ($mapper !== null) $this->setMapper($mapper);
    }

    public function setCursor(\MongoCursor $cursor)
    {
        $this->cursor = $cursor;

        return $this;
    }

    public function getCursor()
    {
        return $this->cursor;
    }

    public function current()
    {
        $result = $this->getCursor()->current();
        $mapper = $this->getMapper();

        if ($mapper && $result) {
            return Echidna::buildDocument($mapper->getDocument(), $result, false, $mapper, ['after_get']);
        }

        return null;
    }

    public function next()
    {
        $result = $this->getCursor()->next();
        $mapper = $this->getMapper();

        if ($mapper && $result) {
            return Echidna::buildDocument($mapper->getDocument(), $result, false, $mapper, ['after_get']);
        }

        return null;
    }

    public function key()
    {
        return $this->getCursor()->key();
    }

    public function valid()
    {
        return $this->getCursor()->valid();
    }

    public function rewind()
    {
        return $this->getCursor()->rewind();
    }

    public function count()
    {
        return $this->getCursor()->count(false);
    }

    public function getData()
    {
        return iterator_to_array($this);
    }

    public function toArray()
    {
        $data = $this->getData();
        foreach ($data as $key => $value) {
            if ($value instanceof DocumentInterface) {
                $data[$key] = $value->toArray();
            }
        }

        return $data;
    }

    public function with($refs)
    {
        $mapper = $this->getMapper();
        if (empty($mapper)) return $this;

        $refs = $this->resolveReferences($refs);
        if (empty($refs)) return $this;

        $data = $this->getData();
        if (empty($data)) return $this;

        $type = new IdType();
        foreach ($refs as $offset => $ref) {
            $detail_field = $ref['field'];
            $lookup_ids   = [];
            foreach ($data as $value) {
                $detail_id = isset($value[$detail_field]) && $type->validate($value[$detail_field]) ? $type->filterMongo($value[$detail_field]) : null;
                if ($detail_id) $lookup_ids[] = $detail_id;
            }

            if (empty($lookup_ids)) continue;

            $detail_mapper = Echidna::buildMapper($this->getMapper()->getDatabase(), $ref['document']);
            $detail_result = $detail_mapper->find(['_id' => ['$in' => $lookup_ids]]);
            $detail_result = $detail_result->getData();

            foreach ($data as $key => $value) {
                $detail_id    = isset($value[$detail_field]) && $type->validate($value[$detail_field]) ? $type->filter($value[$detail_field]) : null;
                $detail_value = isset($detail_result[$detail_id]) ? $detail_result[$detail_id] : null;

                $data[$key][$offset] = $detail_value;
            }
        }

        return $data;
    }

    private function resolveReferences($refs)
    {
        $mapper = $this->getMapper();
        if (!$mapper) return [];

        $document = $mapper->getDocument();
        $refs     = is_array($refs) ? $refs : [$refs];
        $result   = [];

        foreach ($refs as $offset) {
            $ref = Echidna::buildReference($document, $offset);
            if ($ref['field']) $result[$offset] = $ref;
        }

        return $result;
    }
}