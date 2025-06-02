<?php
class UserModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getUserByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser(string $username, string $email, string $password): string|bool {
        try {
            // Check for existing email
            if ($this->getUserByEmail($email)) {
                return "Email already exists";
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );

            $success = $stmt->execute([$username, $email, $hashedPassword]);
            
            return $success ? true : "Database error";
        } catch (PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return "System error";
        }
    }
}