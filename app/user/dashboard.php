<?php

session_start();


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?erro=nao_logado");
    exit(); 
}

$nome_usuario = htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8');

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OneFeed</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        p { font-size: 1.1em; }
        a { color: #d9534f; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    
    <div class="container">
        <h1>Bem-vindo ao OneFeed, <?php echo $nome_usuario; ?>!</h1>
        <p>Este é o seu dashboard. Você conseguiu se conectou com sucesso.</p>
        <br>
        <p><a href="logout.php">Sair (Logout)</a></p>
    </div>

</body>
</html>