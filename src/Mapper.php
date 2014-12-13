<?php

namespace Echidna;

class Mapper implements MapperInterface
{

    use DocumentBuilderTrait;

    /** @var  \MongoDB */
    private $database;

    public function __construct(\MongoDB $database, $document)
    {
        $this->setDatabase($database)->setDocument($document);
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
        $database   = $this->getDatabase();
        $document   = $this->getDocument();
        $collection = $document::collection();

        return $database->$collection;
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

        return new Cursor($cursor, $this->getDocument());
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
     * @param DocumentInterface $document
     */
    public function save($document)
    {
        if (is_array($document)) $document = $this->build($document);
        if (!($document instanceof DocumentInterface)) throw new \InvalidArgumentException();

        $result = $this->getCollection()->save($document->getData());

        if (
            $result['ok'] != 1
            || !empty($result['err'])
        ) {
            throw new \Exception($result['errmsg']);
        }

        return true;
    }

} 