<?php

namespace EchidnaTest;

use Echidna\Echidna;

class ReferencesTest extends Base
{

    private $cities = [];
    private $departments = [];
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
            $mapper     = Echidna::mapper($this->database, "EchidnaTest\\Document\\DepartmentDocument");
            $department = Echidna::document("EchidnaTest\\Document\\DepartmentDocument", ['name' => 'department' . $i]);

            $mapper->save($department);

            $this->departments[$department['_id']] = $department;
        }

        for ($i = 0; $i < 5; $i++) {
            $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\UserDocument");
            $user   = Echidna::document(
                             "EchidnaTest\\Document\\UserDocument", [
                                 'username'      => 'username' . $i,
                                 'password'      => 'password' . $i,
                                 'email'         => 'username' . $i . '@example.com',
                                 'city_id'       => array_rand($this->cities, 1),
                                 'department_id' => array_rand($this->departments, 1)
                             ]);

            $mapper->save($user);

            $this->users[$user['_id']] = $user;
        }
    }

    public function testLazyLoading()
    {
        foreach ($this->users as $user) {
            $city_id   = $user['city_id'];
            $user_city = $user['city'];

            $this->assertInstanceOf("EchidnaTest\\Document\\CityDocument", $user_city);
            $this->assertEquals($this->cities[$city_id]['name'], $user_city['name']);

            $department_id   = $user['department_id'];
            $user_department = $user['department'];

            $this->assertInstanceOf("EchidnaTest\\Document\\DepartmentDocument", $user_department);
            $this->assertEquals($this->departments[$department_id]['name'], $user_department['name']);

        }
    }

    public function testEagerLoading()
    {
        $mapper = Echidna::mapper($this->database, "EchidnaTest\\Document\\UserDocument");
        $users  = $mapper->all()->with(['city', 'department']);

        foreach ($users as $user) {
            $city_id   = $user['city_id'];
            $user_city = $user['city'];

            $this->assertInstanceOf("EchidnaTest\\Document\\CityDocument", $user_city);
            $this->assertEquals($this->cities[$city_id]['name'], $user_city['name']);

            $department_id   = $user['department_id'];
            $user_department = $user['department'];

            $this->assertInstanceOf("EchidnaTest\\Document\\DepartmentDocument", $user_department);
            $this->assertEquals($this->departments[$department_id]['name'], $user_department['name']);

        }
    }

} 