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
        
        $comentario_id = $this->db->lastInsertId();

        try {
            $postDAO = new Post(); 
            $post = $postDAO->getById($post_id);
            
            if ($post) {
                $post_autor_id = $post['autor_id'];
                
                $notificacaoDAO = new Notificacao();
                $notificacaoDAO->create($post_autor_id, $autor_id, $post_id, $comentario_id);
            }

        } catch (Exception $e) {
            error_log("Erro ao criar notificação: " . $e->getMessage());
        }
        
        return $comentario_id;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM Comentario WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByPostId($post_id) {
        $sql = "SELECT 
                c.*, u.nome as autor_nome 
            FROM 
                Comentario c 
            JOIN 
                Usuario u ON c.autor_id = u.id 
            WHERE 
                c.post_id = ? 
            ORDER BY 
                c.dataHora ASC";
            
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$post_id]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
?>