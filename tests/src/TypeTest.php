<?php

namespace EchidnaTest;

use Echidna\Type;

class TypeTest extends \PHPUnit_Framework_TestCase
{

    public function testDate()
    {
        $type = Type::factory('date');

        $this->assertInstanceOf("Echidna\\Type\\DateType", $type);

        $php_value   = new \DateTime();
        $mongo_value = new \MongoDate($php_value->format('U'));

        $this->assertInstanceOf('DateTime', $type->filter($php_value));
        $this->assertInstanceOf('DateTime', $type->filter($php_value->getTimestamp()));
        $this->assertInstanceOf('DateTime', $type->filter($mongo_value));
        $this->assertInstanceOf('MongoDate', $type->filterMongo($php_value));
        $this->assertInstanceOf('MongoDate', $type->filterMongo($php_value->getTimestamp()));
        $this->assertInstanceOf('MongoDate', $type->filterMongo($mongo_value));

        $this->assertEquals($php_value, $type->filter($php_value));
        $this->assertEquals($mongo_value, $type->filterMongo($mongo_value));

        $this->assertNull($type->filter(null));
        $this->assertNull($type->filterMongo(null));
    }

    public function testId()
    {
        $type = Type::factory('id');

        $this->assertInstanceOf("Echidna\\Type\\IdType", $type);

        $mongo_value = new \MongoId();
        $php_value   = (string) $mongo_value;

        $this->assertInternalType('string', $type->filter($php_value));
        $this->assertInternalType('string', $type->filter($mongo_value));
        $this->assertInstanceOf('MongoId', $type->filterMongo($php_value));
        $this->assertInstanceOf('MongoId', $type->filterMongo($mongo_value));

        $this->assertEquals($php_value, $type->filter($php_value));
        $this->assertEquals($mongo_value, $type->filterMongo($mongo_value));
    }
} 