<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$allowed_files = ['style.css', 'style2.css', 'style3.css'];
$directory = 'css';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $filename = $_POST['filename'];
    $content = $_POST['content'];

    if (!in_array($filename, $allowed_files)) {
        echo "<div class='alert alert-danger'>Você só pode editar os arquivos style.css, style2.css e style3.css.</div>";
        exit;
    }

    $filepath = $directory . '/' . $filename;

    switch ($action) {
        case 'edit':
            if (file_exists($filepath)) {
                $content = file_get_contents($filepath);
            } else {
                echo "<div class='alert alert-warning'>Arquivo não existe.</div>";
            }
            break;
        case 'save':
            file_put_contents($filepath, $content);
            break;
        default:
            echo "<div class='alert alert-danger'>Ação não permitida.</div>";
            break;
    }
}

$files = scandir($directory);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Editor de CSS</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
</head>
<body>
<div class="container">
    <form method="post" id="edit-form" class="mt-5">
        <input type="hidden" name="action" value="edit">
        <div class="form-group">
            <label for="filename">Nome do arquivo:</label>
            <select id="filename" name="filename" class="form-control">
                <option value="">Selecione</option>
                <?php foreach ($allowed_files as $file) : ?>
                    <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="submit" value="Submit" class="btn btn-primary d-none">
    </form>

    <form method="post" id="save-form" class="mt-5">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="filename" value="<?php echo $filename; ?>">
        <div class="form-group">
            <label for="content">Conteúdo:</label>
            <textarea id="content" name="content" class="form-control" rows="10"><?php echo htmlspecialchars($content); ?></textarea>
        </div>
        <input type="submit" value="Salvar" class="btn btn-primary">
    </form>

    <a href="editor.php" class="btn btn-secondary mt-5">Abrir Editor HTML</a>
</div>

<script>
$(document).ready(function() {
    $('#filename').change(function() {
        $('#edit-form').submit();
    });
});
</script>

</body>
</html>