<?php

require_once __DIR__ . '/../database/Database.php';

class Amizade {
    
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function enviarSolicitacao($solicitante_id, $receptor_id) {
        if ($solicitante_id == $receptor_id) return false;

        $status = $this->getStatusAmizade($solicitante_id, $receptor_id);
        if ($status != null) {
            return false;
        }

        $sql = "INSERT INTO Amizade (solicitante_id, receptor_id, status) VALUES (?, ?, 'pendente')";
        
        $stmt = $this->db->prepare($sql);
        try {
            return $stmt->execute([$solicitante_id, $receptor_id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function responderSolicitacao($solicitacao_id, $receptor_id, $nova_acao) {
        if ($nova_acao == 'aceitar') {
            $sql = "UPDATE Amizade 
                    SET status = 'aceita', data_aceite = NOW() 
                    WHERE id = ? AND receptor_id = ? AND status = 'pendente'";
        } else if ($nova_acao == 'rejeitar') {
            $sql = "DELETE FROM Amizade 
                    WHERE id = ? AND receptor_id = ? AND status = 'pendente'";
        } else {
            return false;
        }
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$solicitacao_id, $receptor_id]);
    }

    public function getStatusAmizade($usuario1_id, $usuario2_id) {
        $sql = "SELECT status FROM Amizade 
                WHERE (solicitante_id = ? AND receptor_id = ?) 
                   OR (solicitante_id = ? AND receptor_id = ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario1_id, $usuario2_id, $usuario2_id, $usuario1_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['status'] : null;
    }

    public function getSolicitacoesPendentes($receptor_id) {
        $sql = "SELECT a.*, u.nome as solicitante_nome 
                FROM Amizade a
                JOIN Usuario u ON a.solicitante_id = u.id
                WHERE a.receptor_id = ? AND a.status = 'pendente'
                ORDER BY a.data_solicitacao DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$receptor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getContagemSolicitacoesPendentes($receptor_id) {
        $sql = "SELECT COUNT(*) FROM Amizade 
                WHERE receptor_id = ? AND status = 'pendente'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$receptor_id]);
        return (int)$stmt->fetchColumn();
    }

    public function getAmigos($usuario_id) {
        $sql = "
            (SELECT 
                a.id as amizade_id, 
                u.id as amigo_id, 
                u.nome as amigo_nome 
            FROM Amizade a
            JOIN Usuario u ON a.receptor_id = u.id
            WHERE a.solicitante_id = ? AND a.status = 'aceita')
            
            UNION
            
            (SELECT 
                a.id as amizade_id, 
                u.id as amigo_id, 
                u.nome as amigo_nome 
            FROM Amizade a
            JOIN Usuario u ON a.solicitante_id = u.id
            WHERE a.receptor_id = ? AND a.status = 'aceita')
            
            ORDER BY amigo_nome ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id, $usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removerAmizade($usuario_id, $amigo_id) {
        $sql = "DELETE FROM Amizade 
                WHERE (solicitante_id = ? AND receptor_id = ? AND status = 'aceita') 
                   OR (solicitante_id = ? AND receptor_id = ? AND status = 'aceita')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $amigo_id, $amigo_id, $usuario_id]);
    }
}
?>