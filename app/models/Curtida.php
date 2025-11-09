<?php
require_once '../database/Database.php';

class Curtida {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function checkLike($usuario_id, $post_id) {
        $sql = "SELECT COUNT(*) FROM Curtida WHERE usuario_id = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $post_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function addLike($usuario_id, $post_id) {
        $sql = "INSERT INTO Curtida (usuario_id, post_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        try {
            return $stmt->execute([$usuario_id, $post_id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeLike($usuario_id, $post_id) {
        $sql = "DELETE FROM Curtida WHERE usuario_id = ? AND post_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $post_id]);
    }

    public function getLikesByUsuario($usuario_id) {
        $sql = "SELECT post_id FROM Curtida WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); 
    }
}
?>