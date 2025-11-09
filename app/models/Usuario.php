<?php
require_once '../database/Database.php';

class Usuario {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($nome, $dataNascimento, $genero, $email, $senhaHash) {
        $sql = "INSERT INTO Usuario (nome, dataNascimento, genero, email, senha) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nome, $dataNascimento, $genero, $email, $senhaHash]);
        
        return $this->db->lastInsertId();
    }

    public function getById($id) {
    $stmt = $this->db->prepare("SELECT * FROM Usuario WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getByEmail($email) {
    $stmt = $this->db->prepare("SELECT * FROM Usuario WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Usuario WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>