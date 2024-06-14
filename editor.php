<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

    include 'Database.php';

    $db = new Database();

    $arquivo = ['id' => '', 'nome' => '', 'conteudo' => '', 'pasta' =>''];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $conteudo = $_POST['conteudo'];
    $pasta = $_POST['pasta'];

    if ($id) {
        $db->atualizar($id, $nome, $conteudo, $pasta);
    } else {
        $db->inserir($nome, $conteudo, $pasta);
    }

    $db->gerarArquivoHTML($nome, $conteudo, $pasta);

    header('Location: editor.php');
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
        $arquivo = $db->buscarArquivo($_GET['id']);
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
        $arquivo = $db->buscarArquivo($_GET['excluir']);
        $caminhoDoArquivo = $arquivo['pasta'] . '/' . $arquivo['nome'] . '.html';
        if (file_exists($caminhoDoArquivo)) {
        unlink($caminhoDoArquivo);
        }
        $db->excluir($_GET['excluir']);
        header('Location: editor.php');
    }
    $arquivos = $db->buscar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciador de Arquivos HTML</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Arquivos HTML</h1>

        <form method="post">
            <input type="hidden" name="id" value="<?php echo $arquivo['id']; ?>">
            <div class="form-group">
                <label for="nome">Nome do Arquivo:</label>
                <input type="text" class="form-control" name="nome" placeholder="nome-do-arquivo" value="<?php echo $arquivo['nome']; ?>">
                <small id="nomeHelp" class="form-text text-muted">Ensira o nome do arquivo html sem o <code>.html</code></small>
            </div>
            <div class="form-group">
                <label for="pasta">Nome da Pasta:</label>
                <input type="text" class="form-control" name="pasta" placeholder="nome-da-pasta" value="<?php echo $arquivo['pasta']; ?>">
                </div>
            <div class="form-group">
                <label for="conteudo">Conteúdo HTML:</label>
                <textarea class="form-control" name="conteudo" rows="10"><?php echo $arquivo['conteudo']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>

        <h2>Arquivos Criados:</h2>
        <ul class="list-group">
            <?php while ($arquivo = $arquivos->fetchArray()) { ?>
                <li class="list-group-item">
                    <?php echo $arquivo['nome']; ?>
                    <a href="?id=<?php echo $arquivo['id']; ?>" class="btn btn-primary float-right">Editar</a>
                    <a href="?excluir=<?php echo $arquivo['id']; ?>" class="btn btn-danger float-right mr-2">Excluir</a>
                </li>
            <?php } ?>
        </ul>
        <a href="css.php" class="btn btn-secondary mt-5">Abrir Editor CSS</a>
    </div>
</body>
</html>