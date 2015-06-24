<?php

namespace SampleApp\Test\Web;

use TigerKit\Models\User;

class UserTest extends BaseWebTest {

  protected $testUserUsername;
  protected $testUserPassword;
  /** @var User */
  protected $testUser;


  public function tearDown(){
    $this->testUser->delete();
  }

  public function testValidLogin(){
    $loginResponse = $this->doRequest("POST","/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);

    //$this->assertTrue($loginResponse->isOk());
    $this->assertEquals(302, $loginResponse->getStatus());
    $this->assertEquals("/dashboard", $loginResponse->header("location"));
  }

  public function testInvalidLogin(){
    $loginResponse = $this->doRequest("POST","/login", ['username' => $this->testUserUsername, 'password' => "bogus"]);

    //$this->assertTrue($loginResponse->isOk());
    $this->assertEquals(302, $loginResponse->getStatus());
    $this->assertEquals("/login?failed", $loginResponse->header("location"));
  }

}
