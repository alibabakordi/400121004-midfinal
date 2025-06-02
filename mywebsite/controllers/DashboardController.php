<?php
require_once '../models/PostModel.php';

class DashboardController {
    private $postModel;

    public function __construct() {
        global $pdo;
        $this->postModel = new PostModel($pdo);
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?page=login');
            exit;
        }
        $posts = $this->postModel->getPostsByUser($_SESSION['user_id']);
        require_once '../views/dashboard/index.php';
    }
	public function createPost() {
		session_start();
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
			$title = $_POST['title'];
			$content = $_POST['content'];
			$imagePath = null;

			// Handle file upload
			if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
				$uploadDir = 'assets/uploads/';
				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0777, true);
				}
				$imageName = uniqid() . '_' . basename($_FILES['image']['name']);
				$targetPath = $uploadDir . $imageName;
				if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
					$imagePath = $targetPath;
            }
        }

			if ($this->postModel->createPost($_SESSION['user_id'], $title, $content, $imagePath)) {
				header('Location: ?page=dashboard');
        }
    }
}
}
?>