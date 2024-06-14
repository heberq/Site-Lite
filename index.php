<?php
// Conecta ao banco de dados SQLite
$db = new PDO('sqlite:meu_banco_de_dados.db');

// Seleciona os nomes das páginas do banco de dados
$stmt = $db->query('SELECT nome, pasta FROM arquivos');
$paginas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <?php
    // Obtém o nome da página da URL reescrita
    $nomePagina = trim($_GET['pagina'] ?? '', '/');
    // Se a página não for especificada, use um título padrão
    if ($nomePagina == '') {
        $nomePagina = 'Bem Vindo ao Site Lite';
    }
    ?>
    <title><?php echo htmlspecialchars(ucwords(str_replace('-', ' ', $nomePagina))); ?></title>
    <!-- Inclui o CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php foreach ($paginas as $pagina): ?>
                    <?php if($pagina['nome'] != 'header' && $pagina['nome'] != 'footer'): ?>
                        <li class="nav-item">
                            <?php $nomePagina = str_replace('-', ' ', $pagina['nome']);
                                  $nomePagina = ucwords($nomePagina); // Adicionado esta linha
                            ?>
                            <a class="nav-link" href="/<?php echo urlencode($pagina['nome']); ?>"><?php echo htmlspecialchars($nomePagina); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
    <?php
        if ($pagina) {
                        $arquivo = $pagina['pasta'] . '/header.html';
                     }
                        // Verifica se o arquivo existe e está na pasta correta
        if (file_exists($arquivo) && is_file($arquivo)) {
        // Importa o arquivo HTML
            include($arquivo);
        } else {
            // Se o arquivo não existir, inclui a página de erro
            echo '<div class="alert alert-danger" role="alert">Crie uma página obrigatória <strong>header</strong>.</div>';
        }
    ?>
    <?php
    // Define o arquivo padrão para incluir
    $arquivo = 'html/home.html';

    // Obtém o nome da página da URL reescrita
    $nomePagina = trim($_GET['pagina'] ?? '', '/');

    // Busca o nome da pasta no banco de dados
    $stmt = $db->prepare('SELECT pasta FROM arquivos WHERE nome = ?');
    $stmt->execute([$nomePagina]);
    $pagina = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se a página existir no banco de dados, define o caminho do arquivo
    if ($pagina) {
        $arquivo = $pagina['pasta'] . '/' . $nomePagina . '.html';
    }

    // Verifica se o arquivo existe e está na pasta correta
    if (file_exists($arquivo) && is_file($arquivo)) {
        // Importa o arquivo HTML
        include($arquivo);
    } else {
        // Se o arquivo não existir, inclui a página de erro
        echo '<div class="alert alert-danger" role="alert">Crie uma página obrigatória <strong>home</strong>.</div>';
    }
    ?>
    <?php
        if ($pagina) {
                        $arquivo = $pagina['pasta'] . '/footer.html';
                     }
                        // Verifica se o arquivo existe e está na pasta correta
        if (file_exists($arquivo) && is_file($arquivo)) {
        // Importa o arquivo HTML
            include($arquivo);
        } else {
            // Se o arquivo não existir, inclui a página de erro
            echo '<div class="alert alert-danger" role="alert">Crie uma página obrigatória <strong>footer</strong>.</div>';
        }
    ?>
</div>

<!-- Inclui os scripts do Bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>