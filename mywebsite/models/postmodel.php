<?php
class PostModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getPostsByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
	public function createPost($userId, $title, $content) {
    $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $title, $content]);
}
	public function getAllPosts() {
    $stmt = $this->pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
	public function createPost($userId, $title, $content, $imagePath = null) {
    $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, title, content, image_path) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$userId, $title, $content, $imagePath]);
}
	public function getPaginatedPosts($limit, $offset) {
    $stmt = $this->pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>