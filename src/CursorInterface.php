<?php

namespace Echidna;

interface CursorInterface extends \Iterator, \Countable, DocumentBuilderInterface, ResultInterface
{

    public function __construct(\MongoCursor $cursor, $entity);

    public function getCursor();
} 