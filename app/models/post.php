<?php
require_once '../database/Database.php';

class Post {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($conteudo, $autor_id) {
        $sql = "INSERT INTO Post (conteudo, autor_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$conteudo, $autor_id]);
        return $this->db->lastInsertId();
    }

    public function getByAutorId($autor_id) {
        $sql = "SELECT * FROM Post WHERE autor_id = ? ORDER BY dataHora DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$autor_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM Post WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Post WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function likePost($id) {
        $sql = "UPDATE Post SET curtidas = curtidas + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>