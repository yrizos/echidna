<?php

namespace Echidna;

interface CursorInterface extends \Iterator, \Countable
{

    public function __construct(\MongoCursor $cursor, MapperInterface $mapper = null);

    public function getCursor();

    public function getMapper();

    public function getData();

    public function toArray();

}