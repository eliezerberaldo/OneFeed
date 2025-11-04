<?php

session_start();

require_once '../models/Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $nome_usuario = $_POST['usuario'];
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['genero'];
    $senha = $_POST['password'];
    $confirma_senha = $_POST['confirm-password'];

    if ($senha !== $confirma_senha) {
        header("Location: ../user/register.php?erro=senhas_nao_conferem");
        exit();
    }
    
    if (empty($data_nascimento) || empty($genero)) {
         header("Location: ../user/register.php?erro=campos_obrigatorios");
         exit();
    }
    
    $usuarioObj = new Usuario();
    $usuario_existente = $usuarioObj->getByEmail($email);

    if ($usuario_existente) {
        header("Location: ../user/register.php?erro=email_ja_cadastrado");
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $novo_usuario_id = $usuarioObj->create($nome_usuario, $data_nascimento, $genero, $email, $senha_hash);
        
        if ($novo_usuario_id) {
            $_SESSION['usuario_id'] = $novo_usuario_id;
            $_SESSION['usuario_nome'] = $nome_usuario; 

            header("Location: ../user/dashboard.php");
            exit();
            
        } else {
            header("Location: ../user/register.php?erro=falha_cadastro");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: ../user/register.php?erro=db_error");
        exit();
    }

} else {
    header("Location: ../user/register.php");
    exit();
}
?>