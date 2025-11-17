<?php

session_start();

require_once __DIR__ . '../database/Database.php';
require_once __DIR__ . '../models/Notificacao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit();
}

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

header('Content-Type: application/json');

try {
    $notificacaoDAO = new Notificacao();
    $notificacaoDAO->marcarComoLidas($_SESSION['usuario_id']);

echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno ao marcar notificações como lidas.']);
}
?>