<?php

namespace EchidnaTest;

use EchidnaTest\Document\TestDocument;

class DocumentTest extends \PHPUnit_Framework_TestCase
{

    public function testGetCollection()
    {
        $document = new TestDocument();

        $this->assertEquals('test', $document::collection());
    }

    public function testGetData()
    {
        $document = new TestDocument();
        $raw      = $document->getRawData();
        $data     = $document->getData();

        $this->assertInternalType('array', $data);
        $this->assertEquals(count($raw), count($data));
        $this->assertEquals(array_keys($raw), array_keys($data));

        foreach ($raw as $offset => $value) {
            if (!$value) $value = $document->getDefault($offset);

            $type     = $document->getFieldType($offset);
            $filtered = $type->filter($value);

            $this->assertEquals($filtered, $data[$offset]);
        }
    }

    public function testGetMongoData()
    {
        $document = new TestDocument();
        $data     = $document->getMongoData();
        $raw      = $document->getRawData();

        $this->assertInternalType('array', $data);
        $this->assertEquals(count($raw), count($data));
        $this->assertEquals(array_keys($raw), array_keys($data));

        foreach ($raw as $offset => $value) {
            if (!$value) $value = $document->getDefault($offset);

            $type     = $document->getFieldType($offset);
            $filtered = $type->filterMongo($value);

            $this->assertEquals($filtered, $data[$offset]);
        }
    }
}
