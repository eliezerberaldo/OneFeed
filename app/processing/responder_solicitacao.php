<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || !isset($_POST['solicitacao_id']) || !isset($_POST['acao'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida.']);
    exit();
}

require_once '../database/Database.php';
require_once '../models/Amizade.php';

$receptor_id = $_SESSION['usuario_id'];
$solicitacao_id = (int)$_POST['solicitacao_id'];
$acao = $_POST['acao'];

try {
    $amizadeDAO = new Amizade();
    $sucesso = $amizadeDAO->responderSolicitacao($solicitacao_id, $receptor_id, $acao);
    
    if ($sucesso) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Resposta registrada.']);
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Não foi possível responder.']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao processar resposta.']);
}
?>