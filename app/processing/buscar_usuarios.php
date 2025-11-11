<?php

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id']) || !isset($_GET['termo'])) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida.']);
    exit();
}

require_once '../database/Database.php';
require_once '../models/Usuario.php';

$usuario_logado_id = $_SESSION['usuario_id'];
$termo = trim($_GET['termo']);

if (empty($termo)) {
    echo json_encode(['sucesso' => true, 'usuarios' => []]);
    exit();
}

try {
    $usuarioDAO = new Usuario();
    $usuarios = $usuarioDAO->searchByNome($termo, $usuario_logado_id);
    echo json_encode(['sucesso' => true, 'usuarios' => $usuarios]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao buscar usuários.']);
}
?>