<?php
namespace EchidnaTest;

use Echidna\Echidna;
use EchidnaTest\Document\EventfulDocument;
use EchidnaTest\Document\ComplexDocument;
use EchidnaTest\Document\SimpleDocument;

class MapperTest extends Base
{

    public function testGetDatabase()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\SimpleDocument");

        $this->assertEquals($this->database, $mapper->getDatabase());
    }

    public function testGetCollection()
    {
        $mapper     = Echidna::mapper($this->database, "EchidnaTest\\Document\\SimpleDocument");
        $collection = ComplexDocument::collection();
        $collection = $this->database->$collection;

        $this->assertInstanceOf("MongoCollection", $mapper->getCollection());
        $this->assertEquals($collection, $mapper->getCollection());
    }

    public function testGetDocument()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\SimpleDocument");

        $this->assertEquals("EchidnaTest\\Document\\SimpleDocument", $mapper->getDocument());
    }

    public function testGetEventEmmiter()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\SimpleDocument");

        $this->assertInstanceOf("Sabre\\Event\\EventEmitterInterface", $mapper->getEventEmitter());
    }

    public function testSave()
    {
        $document = new EventfulDocument();
        $mapper   = Echidna::mapper($this->database, $document);

        $document['_id'] = new \MongoId();

        $this->assertTrue($document->isNew());
        $this->assertFalse($document['before_save']);
        $this->assertFalse($document['after_save']);
        $this->assertFalse($document['after_get']);

        $mapper->save($document);

        $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
        $this->assertFalse($document->isNew());
        $this->assertTrue($document['before_save']);
        $this->assertTrue($document['after_save']);
        $this->assertFalse($document['after_get']);

        $mapper->getCollection()->drop();
    }

    public function testGet()
    {
        $document = new EventfulDocument();
        $mapper   = Echidna::mapper($this->database, $document);

        $id = new \MongoId();

        $document['_id']    = $id;
        $document['string'] = 'value';

        $this->assertTrue($document->isNew());
        $this->assertFalse($document['after_get']);

        $mapper->save($document);

        unset($document);

        $document = $mapper->get($id);

        $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
        $this->assertEquals((string)$id, $document['_id']);
        $this->assertFalse($document->isNew());
        $this->assertTrue($document['after_get']);

        $mapper->getCollection()->drop();
    }

    public function testFindOne()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");

        foreach ($this->data as $value) {
            $value    = ['string' => $value];
            $document = new EventfulDocument();
            $document->setData($value);

            $mapper->save($document);
        }

        foreach ($this->data as $value) {

            $document = $mapper->findOne(['string' => $value]);

            $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
            $this->assertEquals($value, $document['string']);
            $this->assertTrue($document['after_get']);
        }

        $mapper->getCollection()->drop();
    }

    public function testFind()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");
        $ids    = [];
        foreach ($this->data as $value) {
            $document = new EventfulDocument();
            $document->setData(['string' => $value]);

            $mapper->save($document);

            if ($value === 'value 2' || $value === 'value 3') $ids[$document['_id']] = $value;
        }

        $result = $mapper->find(['$or' => [
            ['string' => 'value 2'],
            ['string' => 'value 3'],
        ]])->toArray();

        $this->assertEquals(2, count($result));

        foreach ($ids as $id => $value) {
            $this->assertEquals($value, $result[$id]['string']);
            $this->assertTrue($result[$id]['after_get']);
        }

        $mapper->getCollection()->drop();
    }


    public function testAll()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");

        foreach ($this->data as $value) {
            $document = ['string' => $value];

            $mapper->save($document);
        }

        $result = $mapper->all();

        $this->assertInstanceOf("Echidna\\Cursor", $result);
        $this->assertEquals(count($this->data), count($result));

        foreach ($result as $value) {
            $this->assertInstanceOf("Echidna\\DocumentInterface", $value);
            $this->assertTrue($value['after_get']);
        }

        $mapper->getCollection()->drop();
    }

    public function testDelete()
    {
        $mapper             = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");
        $document           = new EventfulDocument();
        $document['string'] = 'value';

        $mapper->save($document);

        $id       = $document['_id'];
        $document = $mapper->get($id);

        $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
        $this->assertEquals($id, $document['_id']);

        $result   = $mapper->delete($id);
        $document = $mapper->get($id);

        $this->assertTrue($result);
        $this->assertNull($document);

        $mapper->getCollection()->drop();
    }

    public function testRemove()
    {
        $mapper   = Echidna::mapper($this->database, "EchidnaTest\\Document\\SimpleDocument");
        $document = new SimpleDocument();
        $document->setData(['string' => 'value']);
        $mapper->save($document);

        $id       = $document['_id'];
        $document = $mapper->get($id);

        $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
        $this->assertEquals($id, $document['_id']);

        $result   = $mapper->remove(['string' => 'value']);
        $document = $mapper->get($id);

        $this->assertTrue($result);
        $this->assertNull($document);

        $mapper->getCollection()->drop();
    }
} 