<?php
// Inicia a sessão
session_start();
 
// Se o usuário já estiver logado, redirecione-o para a página de dashboard
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}
 
// Inclui o arquivo de configuração e o validador de login
require_once "config.php";
require_once "validators/login_validator.php";
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <?php 
        // Exibe a mensagem de sucesso do cadastro, se houver
        if(isset($_SESSION['registration_success'])){
            echo '<div class="alert alert-success">' . $_SESSION['registration_success'] . '</div>';
            unset($_SESSION['registration_success']);
        }

        // Exibe a mensagem de erro de login, se houver
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <h2>Login</h2>
        <p>Por favor, preencha suas credenciais para fazer o login.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="Seu e-mail">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Sua senha">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Entrar">
            </div>
            <p>Ainda não tem uma conta? <a href="register.php">Cadastre-se agora</a>.</p>
        </form>
    </div>
</body>
</html>
