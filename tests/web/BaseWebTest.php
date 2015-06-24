<?php

namespace SampleApp\Test\Web;

use TigerKit\Models\User;
use TigerKit\Test\TigerWebBaseTest;

class BaseWebTest extends TigerWebBaseTest {

  protected $testUserUsername;
  protected $testUserPassword;
  /** @var User */
  protected $testUser;

  public function setUp(){
    parent::setUp();
    $this->testUserUsername = $this->faker->userName;
    $this->testUserPassword = $this->faker->password;
    $this->testUser = new User();
    $this->testUser->username = $this->testUserUsername;
    $this->testUser->displayname = $this->faker->name();
    $this->testUser->email = $this->faker->safeEmail;
    $this->testUser->setPassword($this->testUserPassword);
    $this->testUser->save();
  }

  public function tearDown(){
    $this->testUser->delete();
  }
}
