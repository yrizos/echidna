<?php

namespace EchidnaTest;

use Echidna\Cursor;
use Echidna\Echidna;

class CursorTest extends Base
{
    /** @var \MongoCollection */
    protected $collection;

    public function setUp()
    {
        parent::setUp();

        $this->collection = $this->database->users;

        foreach ($this->data as $value) $this->collection->save($value);
    }

    public function testConstructor()
    {
        $mongo_cursor = $this->collection->find(['$or' => [
            ['username' => 'username2'],
            ['username' => 'username3'],
        ]]);

        $mapper = Echidna::buildMapper($this->database, "EchidnaTest\\Document\\UserDocument");
        $cursor = new Cursor($mongo_cursor, $mapper);

        $this->assertSame($mongo_cursor, $cursor->getCursor());
        $this->assertSame(2, count($cursor));
    }

    public function testBuilder()
    {
        $mongo_cursor = $this->collection->find(['$or' => [
            ['username' => 'username2'],
            ['username' => 'username3'],
        ]]);

        $mapper = Echidna::buildMapper($this->database, "EchidnaTest\\Document\\UserDocument");
        $cursor = new Cursor($mongo_cursor, $mapper);

        foreach ($cursor as $document) {
            $this->assertInstanceOf("EchidnaTest\\Document\\UserDocument", $document);
        }
    }

}