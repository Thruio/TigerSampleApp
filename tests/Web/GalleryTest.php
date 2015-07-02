<?php

namespace SampleApp\Test\Web;

use TigerKit\Services\UserService;

class GalleryTest extends BaseWebTest
{
  public function testLoginPageAvailable()
  {
    $galleryResponse = $this->doRequest("GET", "/gallery");
    $this->assertTrue($galleryResponse->isOk());
  }

  public function testGalleryPageLoggedIn(){
    $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);
    $galleryResponse = $this->doRequest("GET", "/gallery");
    $this->assertTrue($galleryResponse->isOk());
  }

  public function testGalleryUploadLoggedOut(){
    $galleryResponse = $this->doRequest("GET", "/gallery/upload");
    $this->assertEquals(302, $galleryResponse->getStatus());
    $this->assertEquals("/login", $galleryResponse->header("location"));
  }

  public function testGalleryUploadLoggedIn(){
    $loginResponse = $this->doRequest("POST", "/login", ['username' => $this->testUserUsername, 'password' => $this->testUserPassword]);
    $galleryResponse = $this->doRequest("GET", "/gallery/upload");
    $this->assertTrue($galleryResponse->isOk());
  }


}
