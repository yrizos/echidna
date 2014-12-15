<?php

namespace EchidnaTest;

use Echidna\Echidna;

class EventTest extends Base
{

    public function testSave()
    {
        $mapper   = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");
        $document = Echidna::document("EchidnaTest\\Document\\EventfulDocument");

        $this->assertEquals('no', $document['before_save']);
        $this->assertEquals('no', $document['after_save']);

        $mapper->save($document);

        $this->assertEquals('yes', $document['before_save']);
        $this->assertEquals('yes', $document['after_save']);

        $mapper->getCollection()->drop();
    }

    public function testAfterGet()
    {
        $mapper   = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");
        $document = Echidna::document("EchidnaTest\\Document\\EventfulDocument");

        $mapper->save($document);

        $this->assertEquals('no', $document['after_get']);

        $document = $mapper->get($document['_id']);

        $this->assertEquals('yes', $document['after_get']);

        $mapper->getCollection()->drop();
    }

    public function testAfterGetToArray()
    {
        $mapper   = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");
        $document = Echidna::document("EchidnaTest\\Document\\EventfulDocument");

        $mapper->save($document);

        $this->assertEquals('no', $document['after_get']);

        $document = $mapper->get($document['_id']);
        $document = $document->toArray();

        $this->assertEquals('yes', $document['after_get']);

        $mapper->getCollection()->drop();
    }

    public function testCursorAfterGet()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");

        for ($i = 0; $i < 5; $i++) {
            $document = $mapper->build(['index' => $i]);
            $mapper->save($document);

            $this->assertEquals('no', $document['after_get']);
        }

        $all = $mapper->all();

        foreach ($all as $document) {
            $this->assertEquals('yes', $document['after_get']);
        }

        $mapper->getCollection()->drop();
    }

    public function testCursorToArrayAfterGet()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\EventfulDocument");

        for ($i = 0; $i < 5; $i++) {
            $document = $mapper->build(['index' => $i]);
            $mapper->save($document);

            $this->assertEquals('no', $document['after_get']);
        }

        $all = $mapper->all();
        $all = $all->toArray();

        foreach ($all as $document) {
            $this->assertEquals('yes', $document['after_get']);
        }

        $mapper->getCollection()->drop();
    }
} 