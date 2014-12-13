<?php

namespace Echidna;

class ResultSet implements ResultSetInterface
{

    use EntityBuilderTrait;

    /** @var \MongoCursor */
    private $cursor;

    public function __construct(\MongoCursor $cursor, $entity)
    {
        $this->setCursor($cursor)->setEntity($entity);
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

        return $result ? $this->build($result) : null;
    }

    public function next()
    {
        $result = $this->cursor->next();

        return $result ? $this->build($result) : null;
    }

    public function key()
    {
        return $this->cursor->key();
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

    public function toArray()
    {
        $array = [];

        foreach ($this as $value) $array[] = $value->toArray();

        return $array;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

} 