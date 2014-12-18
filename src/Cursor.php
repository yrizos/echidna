<?php

namespace Echidna;

class Cursor implements CursorInterface
{

    use MapperTrait;

    /** @var \MongoCursor */
    private $cursor;

    /**
     * @param \MongoCursor    $cursor
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