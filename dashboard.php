<?php
// Inicializa a sessão
session_start();
 
// Verifica se o usuário está logado, se não, redireciona para a página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1 class="my-5">Olá, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>. Bem-vindo ao estocAI.</h1>
    <p> <a href="logout.php" class="btn btn-danger ml-3">Sair da conta</a> </p>
</body>
</html>