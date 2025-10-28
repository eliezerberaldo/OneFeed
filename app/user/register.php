<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar-se - OneFeed</title>
    <link rel="stylesheet" href="../../public/css/Style_Cad.css">
    <link rel="shortcut icon" href="../../public/img/favicon.ico">
</head>
<body>

    <div class="pagina-container">
        
        <aside class="barra-lateral">
            <img src="../../public/img/Logo_OnF.png" alt="Logo OneFeed">
            <div class="nome-logo">OneFeed</div>
        </aside>

        <main class="formulario-principal">
            <h1>Cadastrar-se</h1>
            
            <form id="formulario-cadastro">
                
                <div class="grupo-formulario">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required placeholder="seuemail@exemplo.com">
                </div>
                
                <div class="grupo-formulario">
                    <label for="usuario">Nome de Usuário:</label>
                    <input type="text" id="usuario" name="usuario" required placeholder="Seu nome de usuário">
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
                Já possui uma conta? <a href="login.php">Entre</a>
            </p>

        </main>
    </div>
</body>
</html>