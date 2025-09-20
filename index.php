<?php
// Inicia a sessão
session_start();
 
// Se o usuário já estiver logado, redireciona para a página do dashboard
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo ao estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/landing.css">
    <link rel="stylesheet" href="css/auth.css"> <!-- Unified button styles -->
</head>
<body class="landing-page">
    <div class="landing-container">
        <div class="landing-content">
            <h1 class="brand-title">estocAI</h1>
            <p class="tagline">Seu assistente inteligente para controle de estoque.</p>
            <p>Gerencie seus produtos, monitore o estoque e tome decisões com mais eficiência.</p>
            <div class="landing-actions">
                <a href="login.php" class="btn btn-primary">Entrar</a>
                <a href="register.php" class="btn btn-secondary">Cadastrar</a>
            </div>
        </div>
    </div>
</body>
</html>