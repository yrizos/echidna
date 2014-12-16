<?php

namespace EchidnaTest;

use Echidna\Echidna;

class ReferencesTest extends Base
{

    private $cities = [];
    private $users = [];

    public function setUp()
    {
        parent::setUp();

        for ($i = 0; $i < 5; $i++) {
            $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\CityDocument");
            $city   = Echidna::document("EchidnaTest\\Document\\CityDocument", ['name' => 'city' . $i]);

            $mapper->save($city);

            $this->cities[$city['_id']] = $city;
        }

        for ($i = 0; $i < 5; $i++) {
            $city_id = array_rand($this->cities, 1);
            $mapper  = Echidna::mapper($this->database, "EchidnaTest\\Document\\UserDocument");
            $user    = Echidna::document(
                              "EchidnaTest\\Document\\UserDocument", [
                                  'username' => 'username' . $i,
                                  'password' => 'password' . $i,
                                  'email'    => 'username' . $i . '@example.com',
                                  'city_id'  => $city_id
                              ]);

            $mapper->save($user);

            $this->users[$user['_id']] = $user;
        }
    }

    public function testLazyLoad()
    {
        foreach ($this->users as $user) {
            $city_id   = $user['city_id'];
            $user_city = $user['city'];

            $this->assertInstanceOf("EchidnaTest\\Document\\CityDocument", $user_city);
            $this->assertEquals($this->cities[$city_id]['name'], $user_city['name']);
        }
    }

} 