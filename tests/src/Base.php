<?php

namespace EchidnaTest;

class Base extends \PHPUnit_Framework_TestCase
{

    /** @var  \MongoDB */
    protected $database;

    protected $data = [];

    public function setUp()
    {
        $connection     = new \MongoClient();
        $this->database = $connection->selectDB('echidna_test');

        for ($i = 0; $i < 5; $i++) {
            $this->data[] = [
                'username'  => 'username' . $i,
                'firstname' => 'firstname' . $i,
                'lastname'  => 'lastname' . $i,
                'email'     => 'username' . $i . '@example.com'
            ];
        }
    }

    public function tearDown()
    {
        $this->database->drop();
    }

} 