<?php
namespace SampleApp\Controllers;

use TigerKit\Services\ImageService;
use TigerKit\Models;

class BoardController extends BaseController
{
  public function showHomepage()
  {
    die("Front page");
  }

  public function showBoard($board){
    die("Board: {$board}");
  }

  public function showThread($board, $thread){
    die("Thread {$thread} in {$board}");
  }
}