<?php
session_start();

require_once '../database/Database.php';
require_once '../models/Usuario.php';
require_once '../models/Post.php';
require_once '../models/Comentario.php';
require_once '../models/Curtida.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_logado_id = $_SESSION['usuario_id'];

$postDAO = new Post();
$usuarioDAO = new Usuario();
$comentarioDAO = new Comentario();
$curtidaDAO = new Curtida();

$erro = "";

try {
    if (isset($_GET['toggle_like_post_id'])) {
        $post_id_to_toggle = (int)$_GET['toggle_like_post_id'];
        $jaCurtiu = $curtidaDAO->checkLike($usuario_logado_id, $post_id_to_toggle);

        if ($jaCurtiu) {
            $curtidaDAO->removeLike($usuario_logado_id, $post_id_to_toggle);
            $postDAO->decrementLike($post_id_to_toggle);
        } else {
            $curtidaDAO->addLike($usuario_logado_id, $post_id_to_toggle);
            $postDAO->incrementLike($post_id_to_toggle);
        }
        header("Location: dashboard.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_post_content'])) {
        $conteudo_post = trim($_POST['new_post_content']);
        if (!empty($conteudo_post)) {
            $postDAO->create($conteudo_post, $usuario_logado_id);
            header("Location: dashboard.php");
            exit();
        } else {
            $erro = "O conteúdo do post não pode estar vazio.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_comment_content'])) {
        $conteudo_comentario = trim($_POST['new_comment_content']);
        $post_id_comentario = (int)$_POST['post_id'];
        if (!empty($conteudo_comentario) && $post_id_comentario > 0) {
            $comentarioDAO->create($conteudo_comentario, $post_id_comentario, $usuario_logado_id);
            header("Location: dashboard.php");
            exit();
        } else {
            $erro = "O conteúdo do comentário não pode estar vazio.";
        }
    }

} catch (Exception $e) {
    $erro = "Ocorreu um erro ao processar sua solicitação.";
}

$todos_os_posts = $postDAO->getAll();
$meus_post_ids_curtidos = $curtidaDAO->getLikesByUsuario($usuario_logado_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed - OneFeed</title>
    <link rel="stylesheet" href="../../public/css/Style_dashboard.css">
</head>
<body>
    <header>
        <h1>OneFeed</h1>
        <a href="logout.php">Sair</a>
    </header>

    <main class="container">

        <section class="new-post-form">
            <form action="dashboard.php" method="POST">
                <textarea name="new_post_content" placeholder="Compartilhe algo com o OneFeed..."></textarea>
                <button type="submit">Postar</button>
            </form>
            <?php if (!empty($erro)): ?>
                <p class="error-message"><?php echo htmlspecialchars($erro); ?></p>
            <?php endif; ?>
        </section>

        <section id="feed">
            <?php if (empty($todos_os_posts)): ?>
                <p style="text-align: center; color: #777;">Ainda não há publicações. Que tal começar o OneFeed?</p>
            <?php else: ?>
                <?php foreach ($todos_os_posts as $post): ?>
                    <?php $comentarios = $comentarioDAO->getByPostId($post['id']); ?>
                    <article class="post">
                        <div class="post-content">
                            <div class="post-header">
                                <span class="post-author"><?php echo htmlspecialchars($post['autor_nome']); ?></span>
                                <span><?php echo date('d/m/Y H:i', strtotime($post['dataHora'])); ?></span>
                            </div>
                            <div class="post-body">
                                <p><?php echo htmlspecialchars($post['conteudo']); ?></p>
                            </div>

                            <div class="post-actions">
                                <?php $usuarioCurtiuEstePost = in_array($post['id'], $meus_post_ids_curtidos); ?>
                                <a href="dashboard.php?toggle_like_post_id=<?php echo $post['id']; ?>"
                                   class="action-button <?php echo $usuarioCurtiuEstePost ? 'liked' : ''; ?>">
                                   👍 <?php echo $usuarioCurtiuEstePost ? 'Descurtir' : 'Curtir'; ?> (<?php echo $post['curtidas']; ?>)
                                </a>
                                <span class="action-button">💬 Comentar (<?php echo count($comentarios); ?>)</span>
                            </div>
                        </div>

                        <div class="comments-section">
                            <?php foreach ($comentarios as $comentario): ?>
                                <div class="comment">
                                    <span class="comment-author"><?php echo htmlspecialchars($comentario['autor_nome']); ?>:</span>
                                    <span class="comment-body"><?php echo htmlspecialchars($comentario['conteudo']); ?></span>
                                </div>
                            <?php endforeach; ?>

                            <form class="comment-form" action="dashboard.php" method="POST">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <input type="text" name="new_comment_content" placeholder="Escreva um comentário...">
                                <button type="submit">Enviar</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
