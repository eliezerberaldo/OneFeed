<?php

require_once '../database/Database.php';
require_once '../models/Usuario.php';
require_once '../models/Post.php';
require_once '../models/Comentario.php';
require_once '../models/Amizade.php';

try {
    $usuario = new Usuario();
    $post = new Post();
    $comentario = new Comentario();

    echo "<h2>testando criação de usuarios</h2>";
    
    $hashJoao = password_hash('senha123', PASSWORD_DEFAULT);
    $idJoao = $usuario->create(
        'João Silva',
        '1990-01-10',
        'M',
        'joao@email.com',
        $hashJoao
    );
    echo "usuario João criado com ID: $idJoao <br>";

    $hashMaria = password_hash('senha456', PASSWORD_DEFAULT);
    $idMaria = $usuario->create(
        'Maria Souza',
        '1995-03-20',
        'F',
        'maria@email.com',
        $hashMaria
    );
    echo "usuario Maria criada com ID: $idMaria <br>";

    echo "<h2>testando criação de posts</h2>";

    $idPostJoao = $post->create("Meu primeiro post no OneFeed!", $idJoao);
    echo "João (ID: $idJoao) criou o post ID: $idPostJoao <br>";

    $idPostMaria = $post->create("Adorando esta nova rede!", $idMaria);
    echo "Maria (ID: $idMaria) criou o post ID: $idPostMaria <br>";

    echo "<h2>testando adicionar comentarios</h2>";

    $idComentario = $comentario->create(
        "Bem-vindo, João!",
        $idPostJoao,
        $idMaria
    );
    echo "Maria comentou (ID: $idComentario) no post de João ($idPostJoao) <br>";

    echo "<h2>testando a busca de dados</h2>";

    $joao = $usuario->getById($idJoao);
    echo "Dados do usuário ID $idJoao: <pre>" . print_r($joao, true) . "</pre>";

    $comentariosPostJoao = $comentario->getByPostId($idPostJoao);
    echo "Comentários no post de João: <pre>" . print_r($comentariosPostJoao, true) . "</pre>";


} catch (PDOException $e) {
    echo "Erro na operação: " . $e->getMessage();
}
?>