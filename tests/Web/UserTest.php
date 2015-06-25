<?php

namespace SampleApp\Test\Web;

class UserTest extends BaseWebTest
{
  public function testValidLogin()
  {
    $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);

    //$this->assertTrue($loginResponse->isOk());
    $this->assertEquals(302, $loginResponse->getStatus());
    $this->assertEquals("/dashboard", $loginResponse->header("location"));
  }

  public function testInvalidLogin()
  {
    $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => "bogus"]);

    //$this->assertTrue($loginResponse->isOk());
    $this->assertEquals(302, $loginResponse->getStatus());
    $this->assertEquals("/login?failed", $loginResponse->header("location"));
  }

}
