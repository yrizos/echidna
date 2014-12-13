<?php

namespace EchidnaTest;

use Echidna\Type\DateType;
use Echidna\Type\FloatType;
use Echidna\Type\IdType;
use Echidna\Type\IntegerType;
use Echidna\Type\StringType;

class TypeTest extends \PHPUnit_Framework_TestCase
{

    public function testId()
    {
        $type = new IdType();

        $php_value   = '548bba2b6abb3c742a000033';
        $mongo_value = new \MongoId($php_value);

        $this->assertInternalType('string', $type->getPHPValue($mongo_value));
        $this->assertInternalType('string', $type->getPHPValue($php_value));
        $this->assertInstanceOf('MongoId', $type->getMongoValue($php_value));
        $this->assertInstanceOf('MongoId', $type->getMongoValue($mongo_value));

        $this->assertEquals($php_value, $type->getPHPValue($mongo_value));
        $this->assertEquals($php_value, $type->getPHPValue($php_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($mongo_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($php_value));

        $this->assertNull($type->getPHPValue(null));
        $this->assertNull($type->getMongoValue(null));
    }

    public function testString()
    {
        $type = new StringType();

        $php_value   = 'Hello World';
        $mongo_value = $php_value;

        $this->assertInternalType('string', $type->getPHPValue($mongo_value));
        $this->assertInternalType('string', $type->getMongoValue($php_value));

        $this->assertEquals($php_value, $type->getPHPValue($mongo_value));
        $this->assertEquals($php_value, $type->getPHPValue($php_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($mongo_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($php_value));

        $this->assertNull($type->getPHPValue(null));
        $this->assertNull($type->getMongoValue(null));
    }

    public function testInteger()
    {
        $type = new IntegerType();

        $php_value   = 1;
        $mongo_value = $php_value;

        $this->assertInternalType('int', $type->getPHPValue($mongo_value));
        $this->assertInternalType('int', $type->getMongoValue($php_value));

        $this->assertEquals($php_value, $type->getPHPValue($mongo_value));
        $this->assertEquals($php_value, $type->getPHPValue($php_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($mongo_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($php_value));

        $this->assertNull($type->getPHPValue(null));
        $this->assertNull($type->getMongoValue(null));
    }

    public function testFloat()
    {
        $type = new FloatType();

        $php_value   = 3.14;
        $mongo_value = $php_value;

        $this->assertInternalType('float', $type->getPHPValue($mongo_value));
        $this->assertInternalType('float', $type->getMongoValue($php_value));

        $this->assertEquals($php_value, $type->getPHPValue($mongo_value));
        $this->assertEquals($php_value, $type->getPHPValue($php_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($mongo_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($php_value));

        $this->assertNull($type->getPHPValue(null));
        $this->assertNull($type->getMongoValue(null));
    }

    public function testDate()
    {
        $type = new DateType();

        $php_value   = new \DateTime();
        $mongo_value = new \MongoDate($php_value->format('U'));

        $this->assertInstanceOf('DateTime', $type->getPHPValue($php_value));
        $this->assertInstanceOf('DateTime', $type->getPHPValue($php_value->getTimestamp()));
        $this->assertInstanceOf('DateTime', $type->getPHPValue($mongo_value));
        $this->assertInstanceOf('MongoDate', $type->getMongoValue($php_value));
        $this->assertInstanceOf('MongoDate', $type->getMongoValue($php_value->getTimestamp()));
        $this->assertInstanceOf('MongoDate', $type->getMongoValue($mongo_value));

        $this->assertEquals($php_value, $type->getPHPValue($php_value));
        $this->assertEquals($mongo_value, $type->getMongoValue($mongo_value));

        $this->assertNull($type->getPHPValue(null));
        $this->assertNull($type->getMongoValue(null));
    }

} 