<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar - OneFeed</title>
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
            <h1>Entrar</h1>
            
            <form id="formulario-login">
                
                <div class="grupo-formulario">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required placeholder="seuemail@exemplo.com">
                </div>

                <div class="grupo-formulario">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required placeholder="Sua senha">
                </div>

                <button type="submit" class="botao-enviar">Confirmar</button>
            </form>
            
            <p class="link-cadastro">
                Não possui uma conta? <a href="register.php">Cadastre-se</a>
            </p>

        </main>
    </div>
</body>
</html>