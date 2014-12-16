<?php

namespace Echidna;

interface CursorInterface extends \Iterator, \Countable, ResultInterface
{

    public function __construct(\MongoCursor $cursor, MapperInterface $mapper = null);

    public function setCursor(\MongoCursor $cursor);

    public function getCursor();

    public function getData();

    public function with($refs);

}