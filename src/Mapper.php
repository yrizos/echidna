<?php

namespace Echidna;

use Sabre\Event\EventEmitter;
use Sabre\Event\EventEmitterInterface;

class Mapper implements MapperInterface
{

    /** @var string */
    private $document;

    /** @var \MongoDB */
    private $database;

    /** @var \MongoCollection */
    private $collection;

    /** @var array */
    private static $eventEmitter;

    /**
     * @param \MongoDB                 $database
     * @param string|DocumentInterface $document
     */
    public function __construct(\MongoDB $database, $document)
    {
        $this->setDatabase($database)->setDocument($document);
    }

    /**
     * @param string|DocumentInterface $document
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setDocument($document)
    {
        $document = Echidna::document_class($document);
        if (null === $document) throw new \InvalidArgumentException('Document ' . $document . ' is invalid.');

        $collection = $document::collection();
        if (!is_string($collection) || empty($collection)) throw new \InvalidArgumentException("Document collection doesn't exist.");

        $this->document   = $document;
        $this->collection = $this->getDatabase()->$collection;

        return $this;
    }

    /**
     * @return string
     */
    final public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param \MongoDB $database
     *
     * @return $this
     */
    public function setDatabase(\MongoDB $database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @return \MongoDB
     */
    final public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return \MongoCollection
     */
    final public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @return EventEmitterInterface
     */
    public function getEventEmitter()
    {
        $document = $this->getDocument();
        if (empty(self::$eventEmitter[$document])) self::$eventEmitter[$document] = new EventEmitter();

        return self::$eventEmitter[$document];
    }

    /**
     * @param DocumentInterface $document
     * @param array             $events
     *
     * @return $this
     */
    public function emit(DocumentInterface $document, array $events = [])
    {
        foreach ($events as $event) $this->getEventEmitter()->emit($event, [$document, $this]);

        return $this;
    }

    /**
     * @param string|array|DocumentInterface $document
     *
     * @return bool
     * @throws \Exception
     */
    public function save(&$document)
    {
        $data = null;

        if (is_array($document)) {
            $data     = $document;
            $document = $this->getDocument();
        }

        $document = Echidna::document($document, $data, false, $this, ['before_save']);
        $result   = $this->getCollection()->save($document->getFilteredData('mongo'));

        if (
            $result['ok'] != 1
            || !empty($result['err'])
        ) {
            throw new \Exception($result['errmsg']);
        }

        $this->emit($document, ['after_save']);

        return true;
    }

    /**
     * @param \MongoId $id
     *
     * @return DocumentInterface
     */
    public function get($id)
    {
        if (!($id instanceof \MongoId)) $id = new \MongoId($id);

        return $this->findOne(['_id' => $id]);
    }

    /**
     * @return CursorInterface
     */
    public function all()
    {
        return $this->find();
    }

    /**
     * @param array $query
     *
     * @return CursorInterface
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

        return
            $result
                ? Echidna::document($this->getDocument(), $result, false, $this, ['after_get'])
                : null;
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
     *
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

} 