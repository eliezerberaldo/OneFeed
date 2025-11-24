<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar-se - OneFeed</title>
    <link rel="stylesheet" href="css/Style_Cad.css">
    <link rel="shortcut icon" href="img/favicon.ico">
</head>
<body>

    <div class="pagina-container">
        
        <aside class="barra-lateral">
            <img src="img/Logo_OnF.png" alt="Logo OneFeed">
            <div class="nome-logo">OneFeed</div>
        </aside>

        <main class="formulario-principal">

            <div id="trilho" class="trilho">
                <div class="indicador"></div>
            </div>
            
            <h1>Cadastrar-se</h1>
            
            <form id="formulario-cadastro" action="../app/processing/cadastro_processamento.php" method="POST">
                
                <div class="grupo-formulario">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required placeholder="seuemail@exemplo.com">
                </div>
                
                <div class="grupo-formulario">
                    <label for="usuario">Nome de Usuário:</label>
                    <input type="text" id="usuario" name="usuario" required placeholder="Seu nome de usuário">
                </div>

                <div class="grupo-formulario">
                    <label for="data_nascimento">Data de Nascimento:</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" required>
                </div>

                <div class="grupo-formulario">
                    <label for="genero">Gênero:</label>
                    <select id="genero" name="genero" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
                
                <div class="grupo-formulario">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required placeholder="Crie uma senha forte">
                </div>
                
                <div class="grupo-formulario">
                    <label for="confirm-password">Confirmar Senha:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required placeholder="Confirme sua senha">
                </div>
                
                <button type="submit" class="botao-enviar">Confirmar</button>
            </form>

            <p class="link-cadastro">
                Já possui uma conta? <a href="index.php">Entre</a>
            </p>

        </main>
    </div>

    <script src="js/dashboard.js" defer></script>
    <script src="js/darkmode.js"></script>
</body>
</html>