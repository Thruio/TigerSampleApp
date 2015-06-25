<?php

namespace SampleApp\Test\Storage;

use SampleApp\Services\UserService;
use TigerKit\Models;
use TigerKit\Test\TigerBaseTest;

class UserServiceTest extends TigerBaseTest
{
  /** @var UserService */
  private $userService;

  public function setUp(){
    parent::setUp();
    $this->userService = new UserService();
  }

  public function testCreateUser(){
    $user = $this->userService->createUser(
      $this->faker->userName,
      $this->faker->name(),
      $this->faker->password,
      $this->faker->safeEmail
    );
    $this->assertTrue($user instanceof Models\User);
    $this->assertGreaterThan(0, $user->user_id);
  }

  public function testDoLogin(){
    $this->assertTrue($this->userService->doLogin($this->testUser->username, $this->testUserPassword));
    $this->assertTrue($this->userService->doLogin($this->testUser->email, $this->testUserPassword));
    $this->assertFalse($this->userService->doLogin($this->testUser->username, "bogus"));
    $this->assertFalse($this->userService->doLogin($this->testUser->email, "bogus"));
    $this->assertFalse($this->userService->doLogin("bogus", $this->testUserPassword));
  }
}