<?php

namespace Echidna;

class Cursor implements CursorInterface
{

    use DocumentBuilderTrait;

    /** @var \MongoCursor */
    private $cursor;

    public function __construct(\MongoCursor $cursor, $document)
    {
        $this->setCursor($cursor)->setDocument($document);
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

    public function current()
    {
        $result = $this->cursor->current();

        return $result ? $this->build($result, false) : null;
    }

    public function next()
    {
        $result = $this->cursor->next();

        return $result ? $this->build($result, false) : null;
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