<?php
namespace Echidna;

interface MapperInterface
{

    public function __construct(\MongoDB $database, $document);

    public function setDocument($document);

    public function getDocument();

    public function setDatabase(\MongoDB $database);

    public function getDatabase();

    public function getCollection();

    public function getEventEmitter();

    public function emit(DocumentInterface $document, array $events = []);

    public function save(&$document);

    public function get($id);

    public function all();

    public function find(array $query = []);

    public function findOne(array $query = []);

    public function delete($id);

    public function remove(array $query = []);
} 