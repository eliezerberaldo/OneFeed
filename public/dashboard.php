<?php
session_start();

require_once '../app/database/Database.php';
require_once '../app/models/Usuario.php';
require_once '../app/models/Post.php';
require_once '../app/models/Comentario.php';
require_once '../app/models/Curtida.php';
require_once '../app/models/Notificacao.php'; 
require_once '../app/models/Amizade.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_logado_id = $_SESSION['usuario_id'];

$postDAO = new Post();
$usuarioDAO = new Usuario();
$comentarioDAO = new Comentario();
$curtidaDAO = new Curtida();
$notificacaoDAO = new Notificacao();
$amizadeDAO = new Amizade();

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
$contagemNaoLidas = $notificacaoDAO->getContagemNaoLidas($usuario_logado_id);
$notificacoes = $notificacaoDAO->getByUsuarioId($usuario_logado_id);

$solicitacoes_pendentes = $amizadeDAO->getSolicitacoesPendentes($usuario_logado_id);
$contagem_solicitacoes = count($solicitacoes_pendentes);
$meus_amigos = $amizadeDAO->getAmigos($usuario_logado_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed - OneFeed</title>
    <link rel="stylesheet" href="css/Style_dashboard.css">
    <link rel="shortcut icon" href="img/favicon.ico">
</head>
<body>
    
    <header>
        <div class="header-logo-name">
    <h1>OneFeed</h1>
    <span class="welcome-message">
        <strong>
            Bem <?php echo ($_SESSION['genero'] === 'M' ? 'vindo' : 'vinda'); ?>, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!
        </strong>
    </span>
</div>
        
        <nav class="header-nav">
            
            <div class="notification-container">
                <button id="friend-request-bell" class="notification-bell">
                    👥
                    <?php if ($contagem_solicitacoes > 0): ?>
                        <span id="friend-request-badge" class="notification-badge">
                            <?php echo $contagem_solicitacoes; ?>
                        </span>
                    <?php endif; ?>
                </button>

                <div id="friend-request-dropdown" class="notification-dropdown" style="display: none;">
                    <div class="notification-header">Pedidos de Amizade</div>
                    <div id="friend-request-list" class="notification-list">
                        <?php if (empty($solicitacoes_pendentes)): ?>
                            <div class="notification-item-empty">Nenhum pedido pendente.</div>
                        <?php else: ?>
                            <?php foreach ($solicitacoes_pendentes as $solicitacao): ?>
                                <div class="friend-request-item" data-id="<?php echo $solicitacao['id']; ?>">
                                    <strong><?php echo htmlspecialchars($solicitacao['solicitante_nome']); ?></strong>
                                    <div class="friend-request-actions">
                                        <button class="btn-accept" data-acao="aceitar">Aceitar</button>
                                        <button class="btn-reject" data-acao="rejeitar">Rejeitar</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="notification-container">
                <button id="notification-bell" class="notification-bell">
                    🔔
                    <?php if ($contagemNaoLidas > 0): ?>
                        <span id="notification-badge" class="notification-badge">
                            <?php echo $contagemNaoLidas; ?>
                        </span>
                    <?php endif; ?>
                </button>

                <div id="notification-dropdown" class="notification-dropdown" style="display: none;">
                    <div class="notification-header">
                        <span>Notificações</span>
                        <button id="clear-notifications-btn" class="clear-notifications-btn" title="Limpar todas">
                            🗑️
                        </button>
                    </div>
                    <div id="notification-list" class="notification-list">
                        <?php if (empty($notificacoes)): ?>
                            <div class="notification-item-empty">Nenhuma notificação nova.</div>
                        <?php else: ?>
                            <?php foreach ($notificacoes as $notif): ?>
                                <a href="#post-<?php echo $notif['post_id']; ?>" 
                                   class="notification-item <?php echo !$notif['lida'] ? 'unread' : ''; ?>">
                                    <strong><?php echo htmlspecialchars($notif['autor_nome']); ?></strong> comentou no seu post:
                                    <span>"<?php echo htmlspecialchars($notif['post_resumo']); ?>..."</span>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <a href="logout.php" class="logout-button">Sair</a>
        </nav>
    </header>
    <main class="container" id="main-container">

        <div class="main-feed">

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
                        
                        <article class="post" id="post-<?php echo $post['id']; ?>"> 
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
        </div>

        <aside class="friends-sidebar">
            
            <div class="friend-search-box">
                <input type="text" id="friend-search-input" placeholder="Buscar usuários...">
                <div id="friend-search-results" class="friend-search-results" style="display: none;">
                    </div>
            </div>
            
            <h3>Amigos</h3>
            <div id="friend-list" class="friend-list">
                <?php if (empty($meus_amigos)): ?>
                    <p class="friend-list-empty">Adicione amigos para vê-los aqui.</p>
                <?php else: ?>
                    <?php foreach ($meus_amigos as $amigo): ?>
                        <div class="friend-item" data-id="<?php echo $amigo['amigo_id']; ?>">
                            <span><?php echo htmlspecialchars($amigo['amigo_nome']); ?></span>
                            <button class="remove-friend-btn" title="Remover amizade">❌</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </aside>

    </main>

    <script src="js/dashboard.js"></script>
</body>
</html>