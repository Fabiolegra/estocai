<?php
// Inicia a sessão para poder ler a mensagem de sucesso
session_start();
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; margin: 100px auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php 
        // Verifica se existe uma mensagem de sucesso na sessão
        if(isset($_SESSION['registration_success'])){
            // Exibe a mensagem e depois a remove da sessão para não ser exibida novamente
            echo '<div class="alert alert-success">' . $_SESSION['registration_success'] . '</div>';
            unset($_SESSION['registration_success']);
        }
        ?>
        <h2>Login</h2>
        <p>O formulário de login pode ser implementado aqui.</p>
        <p>Ainda não tem uma conta? <a href="register.php">Cadastre-se agora</a>.</p>
    </div>
</body>
</html>
