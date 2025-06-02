<?php
require_once '../models/PostModel.php';

class FeedController {
    private $postModel;

    public function __construct() {
        global $pdo;
        $this->postModel = new PostModel($pdo);
    }

    public function index() {
        $posts = $this->postModel->getAllPosts();
        require_once '../views/feed/index.php';
    }
	public function loadMorePosts() {
    $page = $_GET['page'] ?? 1;
    $limit = 5; // Posts per load
    $offset = ($page - 1) * $limit;
    $posts = $this->postModel->getPaginatedPosts($limit, $offset);
    echo json_encode($posts);
}
}
?>