<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Bem-vindo, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="css.php" class="btn btn-primary mt-5">Abrir CSS</a>
    <a href="editor.php" class="btn btn-secondary mt-5">Abrir HTML</a>
</div>
</body>
</html>
