<?php

namespace SampleApp\Test\Web;

use TigerKit\Test\TigerWebBaseTest;

class IndexTest extends TigerWebBaseTest {
  public function testHome() {
    var_dump($this->doRequest("/"));

    $this->assertContains('home', $response->getBody());
  }
}
