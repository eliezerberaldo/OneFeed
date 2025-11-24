<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || !isset($_POST['amigo_id'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida.']);
    exit();
}

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../models/Amizade.php';

$usuario_logado_id = $_SESSION['usuario_id'];
$amigo_id = (int)$_POST['amigo_id'];

try {
    $amizadeDAO = new Amizade();
    
    $sucesso = $amizadeDAO->removerAmizade($usuario_logado_id, $amigo_id);
    
    if ($sucesso) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Amizade removida.']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não foi possível remover a amizade.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro interno ao remover amizade.']);
}
?>