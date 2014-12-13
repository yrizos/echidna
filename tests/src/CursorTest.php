<?php

namespace EchidnaTest;

use Echidna\Cursor;

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

        $cursor = new Cursor($mongo_cursor, "EchidnaTest\\Document\\UserDocument");

        $this->assertSame($mongo_cursor, $cursor->getCursor());
        $this->assertSame("EchidnaTest\\Document\\UserDocument", $cursor->getDocument());
        $this->assertInstanceOf("EchidnaTest\\Document\\UserDocument", $cursor->build([]));
        $this->assertSame(2, count($cursor));
    }

    public function testBuilder()
    {
        $mongo_cursor = $this->collection->find(['$or' => [
            ['username' => 'username2'],
            ['username' => 'username3'],
        ]]);

        $cursor = new Cursor($mongo_cursor, "EchidnaTest\\Document\\UserDocument");

        foreach ($cursor as $document) {
            $this->assertInstanceOf("EchidnaTest\\Document\\UserDocument", $document);
        }
    }

} 