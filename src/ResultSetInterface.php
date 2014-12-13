<?php

namespace Echidna;

interface ResultSetInterface extends \Iterator, \Countable, EntityBuilderInterface, ResultInterface
{

    public function __construct(\MongoCursor $cursor, $entity);

    public function getCursor();

} 