<?php
require_once '../database/Database.php';

class Amizade {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function addAmizade($usuario_id, $amigo_id) {
        $sql = "INSERT INTO Amizade (usuario_id, amigo_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        
        try {
            return $stmt->execute([$usuario_id, $amigo_id]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return false; 
            }
            throw $e;
        }
    }

    public function removeAmizade($usuario_id, $amigo_id) {
        $sql = "DELETE FROM Amizade WHERE usuario_id = ? AND amigo_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $amigo_id]);
    }

    public function getAmigosIds($usuario_id) {
        $sql = "SELECT amigo_id FROM Amizade WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>