<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($username, $password, $role, $managerId) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role, manager_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $hashedPassword, $role, $managerId])) {
            return true;
        }
        return false;
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    public function getAllManagers() {
        $stmt = $this->db->query("SELECT id, username FROM users WHERE role = 'Manager' OR role ='Subordinate'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
