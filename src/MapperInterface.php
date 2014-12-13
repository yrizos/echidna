<?php

namespace Echidna;

interface MapperInterface extends EntityBuilderInterface
{

    public function __construct(\MongoDB $database, $entity);

    public function getDatabase();

    public function getCollection();

    public function get($id);

    public function all();

    public function find(array $query = []);

    public function findOne(array $query = []);

    public function delete($id);

    public function save($entity);

}