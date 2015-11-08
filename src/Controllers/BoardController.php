<?php
namespace SampleApp\Controllers;

use TigerKit\Services\BoardService;
use TigerKit\Models;
use TigerKit\Services\ThreadService;

class BoardController extends BaseController
{
    /**
 * @var BoardService
*/
    private $boardService;
    /**
 * @var ThreadService
*/
    private $threadService;

    public function __construct()
    {
        parent::__construct();
        $this->boardService = new BoardService();
        $this->threadService = new ThreadService();
    }

    public function showHomepage()
    {
        // Get subscriptions and make homepage out of them
    }

    public function showAll()
    {

    }

    public function showNew()
    {

    }

    public function listBoards()
    {
        $boards = $this->boardService->getBoards();
        $this->slim->render('boards/list.phtml', ['boards' => $boards]);
    }

    public function showBoard($board)
    {
        $board = $this->boardService->getBoard($board);
        if (!$board instanceof Models\Board) {
            $this->slim->response()->isNotFound();
        }
        $threads = $this->threadService->getThreads($board);
        $this->slim->render('boards/display.phtml', ['board' => $board, 'threads' => $threads]);
    }

    public function showThread($board, $thread)
    {
        die("Thread {$thread} in {$board}");
    }
}
