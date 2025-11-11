<?php

session_start();

require_once '../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    if (empty($email) || empty($senha)) {
        header("Location: ../user/login.php?erro=campos_vazios");
        exit();
    }

    $usuarioObj = new Usuario();
    $usuario = $usuarioObj->getByEmail($email);
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);
        
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome']; 

        header("Location: ../user/dashboard.php");
        exit();

    } else {
        header("Location: ../user/login.php?erro=credenciais_invalidas");
        exit();
    }

} else {
    header("Location: ../user/login.php");
    exit();
}
?>