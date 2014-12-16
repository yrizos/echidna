<?php

namespace Echidna;

interface MapperInterface
{

    public function __construct(\MongoDB $database, $entity);

    public function getDatabase();

    public function getCollection();

    public function getDocument();

    public function getEventEmitter();

    public function emit(DocumentInterface $document, array $events = []);

    public function get($id);

    public function all();

    public function find(array $query = []);

    public function findOne(array $query = []);

    public function delete($id);

    public function save(&$document);
}