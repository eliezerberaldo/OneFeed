<?php

session_start();

require_once __DIR__ . '/../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    if (empty($email) || empty($senha)) {
        header("Location: ../../public/index.php?erro=campos_vazios");
        exit();
    }

    $usuarioObj = new Usuario();
    $usuario = $usuarioObj->getByEmail($email);
    
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        
        session_regenerate_id(true);
        
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome']; 
        $_SESSION['genero'] = $usuario['genero']; 

        header("Location: ../../public/dashboard.php");
        exit();

    } else {
        header("Location: ../../public/index.php?erro=credenciais_invalidas");
        exit();
    }

} else {
    header("Location: ../../public/index.php");
    exit();
}
?>