<?php
require_once '../database/Database.php';

class Comentario {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($conteudo, $post_id, $autor_id) {
        $sql = "INSERT INTO Comentario (conteudo, post_id, autor_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$conteudo, $post_id, $autor_id]);
        return $this->db->lastInsertId();
    }

    public function getByPostId($post_id) {
        $sql = "SELECT * FROM Comentario WHERE post_id = ? ORDER BY dataHora ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$post_id]);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Comentario WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>