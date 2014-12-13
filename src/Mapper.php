<?php

namespace Echidna;

class Mapper implements MapperInterface
{

    use EntityBuilderTrait;

    /** @var  \MongoDB */
    private $database;

    public function __construct(\MongoDB $database, $entity)
    {
        $this->setDatabase($database)->setEntity($entity);
    }

    private function setDatabase(\MongoDB $database)
    {
        $this->database = $database;

        return $this;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getCollection()
    {
        $entity     = $this->getEntity();
        $collection = $entity::collection();

        return $this->database->$collection;
    }

    public function get($id)
    {
        if (!($id instanceof \MongoId)) $id = new \MongoId($id);

        return $this->findOne(['_id' => $id]);
    }

    public function all()
    {
        return $this->find();
    }

    /**
     * @todo
     * @param array $query
     */
    public function find(array $query = [])
    {
        $cursor = $this->getCollection()->find($query);

        return new ResultSet($cursor, $this->getEntity());
    }

    /**
     * @todo
     * @param array $query
     */
    public function findOne(array $query = [])
    {
        $result = $this->getCollection()->findOne($query);

        return
            $result
                ? $this->build($result)
                : null;
    }

    /**
     * @todo
     * @param $id
     */
    public function delete($id)
    {

    }

    /**
     * @todo
     * @param EntityInterface $entity
     */
    public function save($entity)
    {
        if (is_array($entity)) $entity = $this->build($entity);
        if (!($entity instanceof EntityInterface)) throw new \InvalidArgumentException();

        $result = $this->getCollection()->save($entity->getData());

        if (
            $result['ok'] != 1
            || !empty($result['err'])
        ) {
            throw new \Exception($result['errmsg']);
        }

        return true;
    }

} 