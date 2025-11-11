<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || !isset($_POST['receptor_id'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida.']);
    exit();
}

require_once '../database/Database.php';
require_once '../models/Amizade.php';

$solicitante_id = $_SESSION['usuario_id'];
$receptor_id = (int)$_POST['receptor_id'];

try {
    $amizadeDAO = new Amizade();
    $sucesso = $amizadeDAO->enviarSolicitacao($solicitante_id, $receptor_id);
    
    if ($sucesso) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Solicitação enviada!']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não foi possível enviar a solicitação. (Talvez já exista?)']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao processar solicitação.']);
}
?>