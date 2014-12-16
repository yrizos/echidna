<?php

namespace EchidnaTest;

use Echidna\Echidna;

class EchidnaTest extends Base
{

    public function testDocument()
    {
        $document = Echidna::buildDocument("EchidnaTest\\Document\\TestDocument", ['integer' => 2], false);

        $this->assertInstanceOf("Echidna\\DocumentInterface", $document);
        $this->assertInstanceOf("EchidnaTest\\Document\\TestDocument", $document);
        $this->assertEquals(2, $document['integer']);
        $this->assertFalse($document->isNew());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDocumentException()
    {
        $document = Echidna::buildDocument('wrong');
    }

    public function testType()
    {
        $type = Echidna::buildType('integer');

        $this->assertInstanceOf("Echidna\\TypeInterface", $type);
        $this->assertInstanceOf("Echidna\\Type\\IntegerType", $type);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTypeException()
    {
        $type = Echidna::buildType('wrong');
    }

    public function testMapper()
    {
        $mapper = Echidna::buildMapper($this->database, "EchidnaTest\\Document\\UserDocument");

        $this->assertInstanceOf("Echidna\\MapperInterface", $mapper);
        $this->assertInstanceOf("Echidna\\Mapper", $mapper);
    }

    public function testCustomMapper()
    {
        $mapper = Echidna::buildMapper($this->database, "EchidnaTest\\Document\\TestDocument");

        $this->assertInstanceOf("Echidna\\MapperInterface", $mapper);
        $this->assertInstanceOf("EchidnaTest\\Document\\TestMapper", $mapper);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMapperException()
    {
        $mapper = Echidna::buildMapper($this->database, 'wrong');
    }
} 