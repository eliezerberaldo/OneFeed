<?php
require_once '../database/Database.php';

class Notificacao {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($usuario_id, $autor_id, $post_id, $comentario_id) {
        if ($usuario_id == $autor_id) {
            return;
        }

        $sql = "INSERT INTO Notificacao (usuario_id, autor_id, post_id, comentario_id, tipo) 
                VALUES (?, ?, ?, ?, 'comentario')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $autor_id, $post_id, $comentario_id]);
    }

    public function getByUsuarioId($usuario_id) {
        $sql = "SELECT 
                    n.*, 
                    u_autor.nome as autor_nome,
                    SUBSTRING(p.conteudo, 1, 40) as post_resumo
                FROM 
                    Notificacao n
                JOIN 
                    Usuario u_autor ON n.autor_id = u_autor.id
                JOIN
                    Post p ON n.post_id = p.id
                WHERE 
                    n.usuario_id = ? 
                ORDER BY 
                    n.dataHora DESC
                LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContagemNaoLidas($usuario_id) {
        $sql = "SELECT COUNT(*) FROM Notificacao WHERE usuario_id = ? AND lida = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return (int)$stmt->fetchColumn();
    }

    public function marcarComoLidas($usuario_id) {
        $sql = "UPDATE Notificacao SET lida = 1 WHERE usuario_id = ? AND lida = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id]);
    }

    public function deleteAllByUsuarioId($usuario_id) {
        $sql = "DELETE FROM Notificacao WHERE usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id]);
    }
}
?>