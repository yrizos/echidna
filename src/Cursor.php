<?php

namespace Echidna;

class Cursor implements CursorInterface
{
    /** @var \MongoCursor */
    private $cursor;

    /** @var MapperInterface */
    private $mapper;

    public function __construct(\MongoCursor $cursor, MapperInterface $mapper = null)
    {
        $this->setCursor($cursor);
        if ($mapper !== null) $this->setMapper($mapper);
    }

    private function setCursor(\MongoCursor $cursor)
    {
        $this->cursor = $cursor;

        return $this;
    }

    public function getCursor()
    {
        return $this->cursor;
    }

    private function setMapper(MapperInterface $mapper)
    {
        $this->mapper = $mapper;

        return $this;
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function current()
    {
        $result = $this->cursor->current();
        $mapper = $this->getMapper();

        if ($mapper && $result) $result = $mapper->build($result, false, ['after_get']);

        return $result ? $result : null;
    }

    public function next()
    {
        $result = $this->cursor->next();
        $mapper = $this->getMapper();

        if ($mapper && $result) $result = $mapper->build($result, false, ['after_get']);

        return $result ? $result : null;
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
            if ($value instanceof DocumentInterface) $value = $value->getData();

            $data[$key] = $value;
        }

        return $data;
    }
} 