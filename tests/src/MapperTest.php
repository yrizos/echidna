<?php

namespace EchidnaTest;

use Echidna\Mapper;
use Echidna\MapperInterface;

class MapperTest extends Base
{
    /** @var  MapperInterface */
    protected $mapper;

    public function setUp()
    {
        parent::setUp();

        $this->mapper = new Mapper($this->database, "EchidnaTest\\Document\\UserDocument");
        $this->mapper->getCollection()->drop();
    }

    public function testConstructor()
    {
        $this->assertSame($this->database, $this->mapper->getDatabase());
        $this->assertInstanceOf("MongoCollection", $this->mapper->getCollection());
        $this->assertEquals("EchidnaTest\\Document\\UserDocument", $this->mapper->getDocument());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorException()
    {
        $this->mapper = new Mapper($this->database, 'wrong');
    }

    public function testBuild()
    {
        $document = $this->mapper->build(['name' => 'yannis']);

        $this->assertInstanceOf("EchidnaTest\\Document\\UserDocument", $document);
        $this->assertEquals('yannis', $document['name']);
    }

    public function testSaveDocument()
    {
        foreach ($this->data as $value) {
            $document = $this->mapper->build($value);

            $this->assertTrue($document->isNew());

            $save = $this->mapper->save($document);

            $this->assertTrue($save);
            $this->assertFalse($document->isNew());
        }

        $this->mapper->getCollection()->drop();
    }

    public function testSaveArray()
    {
        foreach ($this->data as $value) {
            $save = $this->mapper->save($value);

            $this->assertTrue($save);
            $this->assertInstanceOf("Echidna\\DocumentInterface", $value);
            $this->assertFalse($value->isNew());
        }

        $this->mapper->getCollection()->drop();
    }

    public function testFindOne()
    {
        foreach ($this->data as $value) {

            $this->mapper->save($value);

            $query    = ['username' => $value['username']];
            $document = $this->mapper->findOne($query);

            $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
            $this->assertEquals($value['username'], $document['username']);
        }

        $this->mapper->getCollection()->drop();
    }

    public function testFind()
    {
        $ids = [];
        foreach ($this->data as $value) {
            $this->mapper->save($value);

            $ids[$value['username']] = (string) $value['_id'];
        }

        $result = $this->mapper->find(['$or' => [
            ['username' => 'username2'],
            ['username' => 'username3'],
        ]]);

        $result = $result->toArray();

        $this->assertEquals(2, count($result));
        $this->assertEquals('username2', $result[$ids['username2']]['username']);
        $this->assertEquals('username3', $result[$ids['username3']]['username']);

        $this->mapper->getCollection()->drop();
    }

    public function testAll()
    {
        foreach ($this->data as $value) $this->mapper->save($value);

        $result = $this->mapper->all();

        $this->assertInstanceOf("Echidna\\Cursor", $result);

        $result = $result->toArray();

        $this->assertEquals(count($this->data), count($result));

        $result = array_values($result);

        foreach ($result as $key => $value) {
            $this->assertEquals('username' . $key, $value['username']);
        }

        $this->mapper->getCollection()->drop();
    }

    public function testDelete()
    {
        $document = $this->mapper->build(['email' => 'username@example.com']);
        $this->mapper->save($document);

        $id       = $document['_id'];
        $document = $this->mapper->get($id);

        $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
        $this->assertEquals($id, $document['_id']);

        $result   = $this->mapper->delete($id);
        $document = $this->mapper->get($id);

        $this->assertTrue($result);
        $this->assertNull($document);

        $this->mapper->getCollection()->drop();
    }

    public function testRemove()
    {
        $document = $this->mapper->build(['email' => 'username@example.com']);
        $this->mapper->save($document);

        $id       = $document['_id'];
        $document = $this->mapper->get($id);

        $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
        $this->assertEquals($id, $document['_id']);

        $result   = $this->mapper->remove(['email' => 'username@example.com']);
        $document = $this->mapper->get($id);

        $this->assertTrue($result);
        $this->assertNull($document);

        $this->mapper->getCollection()->drop();
    }
}