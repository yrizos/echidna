<?php

namespace Echidna;

class Mapper implements MapperInterface
{

    use DocumentBuilderTrait;

    /** @var  \MongoDB */
    private $database;

    /**
     * @param \MongoDB $database
     * @param DocumentInterface|string $document
     */
    public function __construct(\MongoDB $database, $document)
    {
        $this->setDatabase($database)->setDocument($document);
    }

    /**
     * @param \MongoDB $database
     * @return $this
     */
    private function setDatabase(\MongoDB $database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @return \MongoDB
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return \MongoCollection
     */
    public function getCollection()
    {
        $database   = $this->getDatabase();
        $document   = $this->getDocument();
        $collection = $document::collection();

        return $database->$collection;
    }

    /**
     * @param \MongoId $id
     * @return DocumentInterface
     */
    public function get($id)
    {
        if (!($id instanceof \MongoId)) $id = new \MongoId($id);

        return $this->findOne(['_id' => $id]);
    }

    /**
     * @return Cursor
     */
    public function all()
    {
        return $this->find();
    }

    /**
     * @param array $query
     * @return Cursor
     */
    public function find(array $query = [])
    {
        $cursor = $this->getCollection()->find($query);

        return new Cursor($cursor, $this->getDocument());
    }

    /**
     * @param array $query
     */
    public function findOne(array $query = [])
    {
        $result = $this->getCollection()->findOne($query);

        return $result ? $this->build($result, false) : null;
    }

    /**
     * @param \MongoId $id
     */
    public function delete($id)
    {
        if (!($id instanceof \MongoId)) $id = new \MongoId($id);

        return $this->remove(['_id' => $id]);
    }

    /**
     * @param array $query
     * @return bool
     * @throws \Exception
     */
    public function remove(array $query = [])
    {
        $result = $this->getCollection()->remove($query);

        if (
            $result['ok'] != 1
            || !empty($result['err'])
        ) {
            throw new \Exception($result['errmsg']);
        }

        return true;
    }

    /**
     * @param $document
     * @return $this
     * @throws \InvalidArgumentException
     * @throws \Exception
     */
    public function save(&$document)
    {
        if (is_array($document)) $document = $this->build($document);
        if (!($document instanceof DocumentInterface)) throw new \InvalidArgumentException();

        $result = $this->getCollection()->save($document->getMongoData());

        if (
            $result['ok'] != 1
            || !empty($result['err'])
        ) {
            throw new \Exception($result['errmsg']);
        }

        $document->setNew(false);

        return true;
    }

} 