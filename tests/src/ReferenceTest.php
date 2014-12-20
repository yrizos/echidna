<?php

namespace EchidnaTest;

use Echidna\Mapper;
use Echidna\Reference;
use EchidnaTest\Document\DetailDocument;
use EchidnaTest\Document\MasterDocument;

class ReferenceTest extends Base
{

    protected $ids = [];

    public function setUp()
    {
        parent::setUp();

        $master_mapper = new Mapper($this->database, "EchidnaTest\\Document\\MasterDocument");
        $detail_mapper = new Mapper($this->database, "EchidnaTest\\Document\\DetailDocument");

        for ($i = 0; $i < 3; $i++) {
            $document         = new MasterDocument();
            $document['name'] = 'master ' . $i;

            $master_mapper->save($document);

            $master_id             = (string) $document['_id'];
            $this->ids[$master_id] = [];
        }

        for ($i = 0; $i < 10; $i++) {
            $master_id             = array_rand($this->ids);
            $document              = new DetailDocument();
            $document['name']      = 'detail ' . $i;
            $document['master_id'] = $master_id;

            $detail_mapper->save($document);

            $detail_id               = (string) $document['_id'];
            $this->ids[$master_id][] = $detail_id;
        }


        $this->ids = array_filter($this->ids, function ($value) {
            return !empty($value);
        });
    }

    public function testGetReferences()
    {
        $master     = new MasterDocument();
        $references = $master->getReferences();

        $this->assertInternalType('array', $references);
        $this->assertArrayHasKey('detail', $references);

        $detail = $references['detail'];

        $this->assertEquals(Reference::HAS_MANY, $detail['type']);
        $this->assertEquals('_id', $detail['local_field']);
        $this->assertEquals('master_id', $detail['foreign_field']);
        $this->assertEquals("EchidnaTest\\Document\\MasterDocument", $detail['local_document']);
        $this->assertEquals("EchidnaTest\\Document\\DetailDocument", $detail['foreign_document']);
    }

    public function testLazyLoad1()
    {
        $master_mapper = new Mapper($this->database, "EchidnaTest\\Document\\MasterDocument");

        foreach ($this->ids as $master_id => $detail_ids) {
            $document = $master_mapper->get($master_id);
            $result   = $document['detail'];

            $this->assertInternalType('array', $result);

            foreach ($result as $value) {
                $detail_id = (string) $value['_id'];
                $this->assertInstanceOf("EchidnaTest\\Document\\DetailDocument", $value);
                $this->assertContains($detail_id, $detail_ids);
            }
        }
    }

    public function testLazyLoad2()
    {
        $detail_mapper = new Mapper($this->database, "EchidnaTest\\Document\\DetailDocument");

        foreach ($this->ids as $master_id => $detail_ids) {
            foreach ($detail_ids as $detail_id) {
                $document = $detail_mapper->get($detail_id);

                $this->assertInstanceOf("EchidnaTest\\Document\\DetailDocument", $document);

                $master = $document['master'];

                $this->assertInstanceOf("EchidnaTest\\Document\\MasterDocument", $master);
                $this->assertEquals($master_id, $master['_id']);
            }
        }
    }

    public function testWith()
    {
        $mapper = new Mapper($this->database, "EchidnaTest\\Document\\MasterDocument");
        $result = $mapper->all()->with('detail');

        foreach ($this->ids as $master_id => $detail_ids) {
            $document = $result[$master_id];
            $details  = $document['detail'];

            $this->assertInternalType('array', $details);

            foreach ($details as $value) {
                $detail_id = (string) $value['_id'];

                $this->assertInstanceOf("EchidnaTest\\Document\\DetailDocument", $value);
                $this->assertContains($detail_id, $detail_ids);
            }
        }
    }

}