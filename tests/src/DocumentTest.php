<?php
namespace EchidnaTest;

use Echidna\Echidna;
use EchidnaTest\Document\ComplexDocument;
use EchidnaTest\Document\SimpleDocument;
use EchidnaTest\Document\TestDocument;

class DocumentTest extends Base
{

    public function testGetCollection()
    {
        $document = new SimpleDocument();

        $this->assertEquals('test', $document::collection());
    }

    public function testGetMapper()
    {
        $document = new ComplexDocument();

        $this->assertEquals("EchidnaTest\\Document\\TestMapper", $document::mapper());
    }

    public function testGetFields()
    {
        $document = new ComplexDocument();
        $fields   = $document->getFields();

        $this->assertInternalType('array', $fields);

        foreach ($fields as $key => $value) {
            $key     = $key != '_id' ? explode('_', $key) : ['id'];
            $default = isset($key[1]) && $key[1] == 'default';
            $type    = get_class(Echidna::type($key[0]));

            $this->assertInstanceOf("DataEntity\\TypeInterface", $value);
            $this->assertInstanceOf($type, $value->getType());


            if ($default || $key == ['id']) {
                $this->assertNotNull($value->getDefault());
            } else {
                $this->assertNull($value->getDefault());
            }
        }
    }

}