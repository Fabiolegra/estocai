<?php
// Este arquivo lida com a lógica de validação e processamento do formulário de cadastro.
// Ele é incluído pelo `register.php` e não deve ser acessado diretamente.

// Define variáveis e inicializa com valores vazios
$name = $email = $password = "";
$name_err = $email_err = $password_err = "";

// Processa os dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Valida o nome
    if (empty(trim($_POST["name"]))) {
        $name_err = "Por favor, insira seu nome.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Valida o e-mail
    if (empty(trim($_POST["email"]))) {
        $email_err = "Por favor, insira um e-mail.";
    } else {
        // Prepara uma declaração de seleção para verificar se o e-mail já existe
        $sql = "SELECT id FROM usuarios WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $email_err = "Este e-mail já está em uso.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }

    // Valida a senha
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor, insira uma senha.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Verifica se não há erros de entrada antes de inserir no banco de dados
    if (empty($name_err) && empty($email_err) && empty($password_err)) {
        
        // Prepara uma declaração de inserção
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":nome", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $param_password, PDO::PARAM_STR);

            // Define os parâmetros
            $param_name = $name;
            $param_email = $email;
            // Cria um hash da senha para armazenamento seguro
            $param_password = password_hash($password, PASSWORD_DEFAULT);

            // Tenta executar a declaração preparada
            if ($stmt->execute()) {
                // Armazena uma mensagem de sucesso na sessão
                $_SESSION['registration_success'] = "Cadastro realizado com sucesso! Faça o login.";
                // Redireciona para a página de login
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }
}
?>