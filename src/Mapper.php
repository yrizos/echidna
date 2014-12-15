<?php

namespace Echidna;

class Mapper implements MapperInterface
{
    /** @var string */
    private $document;

    /** @var  \MongoDB */
    private $database;

    /** @var array */
    private static $eventEmitter;

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
     * @param string|DocumentInterface $document
     * @return $this
     * @throws \InvalidArgumentException
     */
    private function setDocument($document)
    {
        if (
            !class_exists($document)
            || !in_array("Echidna\\DocumentInterface", class_implements($document))
        ) throw new \InvalidArgumentException('Document is invalid.');

        $collection = $document::collection();

        if (empty($collection)) throw new \InvalidArgumentException('Collection is invalid.');

        $this->document = $document;

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
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
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

        return new Cursor($cursor, $this);
    }

    /**
     * @param array $query
     */
    public function findOne(array $query = [])
    {
        $result = $this->getCollection()->findOne($query);

        if ($result) {
            $result = $this->build($result, false);
            $this->eventEmitter()->emit('after_get', [$result, $this]);
        }

        return $result ? $result : null;
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
        if ($document instanceof DocumentInterface) {
            $document::events($this->eventEmitter());
        } else if (is_array($document)) {
            $document = $this->build($document);
        }

        if (!($document instanceof DocumentInterface)) throw new \InvalidArgumentException('Document is invalid.');

        $this->eventEmitter()->emit('before_save', [$document, $this]);

        $result = $this->getCollection()->save($document->getMongoData());

        if (
            $result['ok'] != 1
            || !empty($result['err'])
        ) {
            throw new \Exception($result['errmsg']);
        }

        $this->eventEmitter()->emit('after_save', [$document, $this]);

        $document->setNew(false);

        return true;
    }

    /**
     * @param array $data
     * @param bool $isNew
     * @param array $events
     * @return
     */
    public function build(array $data = [], $isNew = true, array $events = [])
    {
        $document = Echidna::document($this->getDocument(), $data, $isNew);
        $document::events($this->eventEmitter());

        foreach ($events as $event) $this->eventEmitter()->emit($event, [$document, $this]);

        return $document;
    }

    /**
     * @return EventEmitter
     */
    public function eventEmitter()
    {
        $document = $this->getDocument();
        if (empty(self::$eventEmitter[$document])) self::$eventEmitter[$document] = new EventEmitter();

        return self::$eventEmitter[$document];
    }
} 