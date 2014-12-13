<?php
//
//namespace EchidnaTest;
//
//use Echidna\Mapper;
//use Echidna\MapperInterface;
//
//class MapperTest extends \PHPUnit_Framework_TestCase
//{
//    /** @var  MapperInterface */
//    protected $mapper;
//
//    /** @var  \MongoDB */
//    protected $database;
//
//    protected $data = [];
//
//    public function setUp()
//    {
//        $connection     = new \MongoClient();
//        $this->database = $connection->selectDB('echidna_test');
//
//        $this->mapper = new Mapper($this->database, "EchidnaTest\\Document\\UserDocument");
//        $this->mapper->getCollection()->drop();
//
//        for ($i = 0; $i < 5; $i++) {
//            $this->data[] = [
//                'username' => 'user' . $i
//            ];
//        }
//    }
//
//    public function testConstructor()
//    {
//        $this->assertSame($this->database, $this->mapper->getDatabase());
//        $this->assertInstanceOf("MongoCollection", $this->mapper->getCollection());
//        $this->assertEquals("EchidnaTest\\Document\\UserDocument", $this->mapper->getDocument());
//    }
//
//    public function testBuild()
//    {
//        $Document = $this->mapper->build(['name' => 'yannis']);
//
//        $this->assertInstanceOf("EchidnaTest\\Document\\UserDocument", $Document);
//        $this->assertEquals('yannis', $Document['name']);
//    }
//
//    public function testSave()
//    {
//        foreach ($this->data as $value) {
//            $result = $this->mapper->save($value);
//
//            $this->assertTrue($result);
//        }
//    }
//
//    public function testFindOne()
//    {
//        foreach ($this->data as $value) {
//
//            $this->mapper->save($value);
//
//            $query  = ['username' => $value['username']];
//            $Document = $this->mapper->findOne($query);
//
//            $this->assertEquals($value['username'], $Document['username']);
//        }
//    }
//
//    public function testFind()
//    {
//        foreach ($this->data as $value) $this->mapper->save($value);
//
//        $result = $this->mapper->find(['$or' => [
//            ['username' => 'user2'],
//            ['username' => 'user3'],
//        ]]);
//
//        $result = $result->toArray();
//
//        $this->assertEquals(2, count($result));
//        $this->assertEquals('user2', $result[0]['username']);
//        $this->assertEquals('user3', $result[1]['username']);
//    }
//
//    public function testAll()
//    {
//        foreach ($this->data as $value) $this->mapper->save($value);
//
//        $result = $this->mapper->all();
//
//        $this->assertInstanceOf("Echidna\\Cursor", $result);
//
//        $result = $result->toArray();
//
//        $this->assertEquals(count($this->data), count($result));
//
//        foreach ($result as $key => $value) {
//            $this->assertEquals('user' . $key, $value['username']);
//        }
//    }
//
//    public function tearDown()
//    {
//        $this->mapper->getDatabase()->drop();
//    }
//
//}