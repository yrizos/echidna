<?php

namespace Echidna;

interface CursorInterface extends \Iterator, \Countable, DocumentBuilderInterface
{

    public function __construct(\MongoCursor $cursor, $entity);

    public function getCursor();

    public function getData();

    public function toArray();

}