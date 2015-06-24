<?php

namespace SampleApp\Test\Web;

use TigerKit\Test\TigerWebBaseTest;

class IndexTest extends TigerWebBaseTest {
  public function testHome() {
    $response = $this->doRequest("GET", "/");
    $this->assertTrue($response->isOk());
  }
}
