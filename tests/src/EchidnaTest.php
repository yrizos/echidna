<?php
namespace EchidnaTest;

use Echidna\Echidna;
use EchidnaTest\Document\TestDocument;

class EchidnaTest extends Base
{

    public function testMapper()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\ComplexDocument");

        $this->assertInstanceOf("Echidna\\MapperInterface", $mapper);
        $this->assertInstanceOf("EchidnaTest\\Document\\TestMapper", $mapper);
    }
} 