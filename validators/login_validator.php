<?php
// Este arquivo lida com a lógica de validação e processamento do formulário de login.
// Ele é incluído pelo `login.php` e não deve ser acessado diretamente.

// Define variáveis e inicializa com valores vazios
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processa os dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Valida o e-mail
    if (empty(trim($_POST["email"]))) {
        $email_err = "Por favor, insira o e-mail.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Valida a senha
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, insira a senha.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Valida as credenciais
    if (empty($email_err) && empty($password_err)) {
        // Prepara uma declaração de seleção
        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            // Vincula variáveis à declaração preparada como parâmetros
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            // Define os parâmetros
            $param_email = $email;

            // Tenta executar a declaração preparada
            if ($stmt->execute()) {
                // Verifica se o e-mail existe, se sim, verifica a senha
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $name = $row["nome"];
                        $hashed_password = $row["senha"];
                        if (password_verify($password, $hashed_password)) {
                            // A senha está correta, então armazena dados na sessão
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;                            
                            
                            // Redireciona o usuário para a página do dashboard
                            header("location: dashboard.php");
                            exit();
                        } else {
                            // A senha não é válida, exibe uma mensagem de erro genérica
                            $login_err = "E-mail ou senha inválidos.";
                        }
                    }
                } else {
                    // O e-mail não existe, exibe uma mensagem de erro genérica
                    $login_err = "E-mail ou senha inválidos.";
                }
            } else {
                echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            // Fecha a declaração
            unset($stmt);
        }
    }
}
?>