<?php
// Este arquivo lida com a lógica de validação e processamento do formulário de cadastro de produtos.

// Define variáveis e inicializa com valores vazios
$nome = $descricao = $quantidade = $quantidade_minima = $preco = "";
$nome_err = $descricao_err = $quantidade_err = $quantidade_minima_err = $preco_err = "";

// Processa os dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Valida o nome
    if (empty(trim($_POST["nome"]))) {
        $nome_err = "Por favor, insira o nome do produto.";
    } else {
        $nome = trim($_POST["nome"]);
    }

    // Descrição é opcional, apenas limpa o valor
    $descricao = trim($_POST["descricao"]);

    // Valida a quantidade
    if (!isset($_POST["quantidade"]) || $_POST["quantidade"] === '') {
        $quantidade_err = "Por favor, insira a quantidade em estoque.";
    } elseif (!ctype_digit($_POST["quantidade"]) || (int)$_POST["quantidade"] < 0) {
        $quantidade_err = "A quantidade deve ser um número inteiro não negativo.";
    } else {
        $quantidade = (int)$_POST["quantidade"];
    }

    // Valida a quantidade mínima
    if (!isset($_POST["quantidade_minima"]) || $_POST["quantidade_minima"] === '') {
        $quantidade_minima_err = "Por favor, insira a quantidade mínima.";
    } elseif (!ctype_digit($_POST["quantidade_minima"]) || (int)$_POST["quantidade_minima"] < 0) {
        $quantidade_minima_err = "A quantidade mínima deve ser um número inteiro não negativo.";
    } else {
        $quantidade_minima = (int)$_POST["quantidade_minima"];
    }

    // Valida o preço
    if (empty(trim($_POST["preco"]))) {
        $preco_err = "Por favor, insira o preço do produto.";
    } elseif (!is_numeric(str_replace(',', '.', $_POST["preco"]))) {
        $preco_err = "O preço deve ser um número válido.";
    } else {
        // Converte vírgula para ponto para o formato do banco de dados
        $preco = str_replace(',', '.', trim($_POST["preco"]));
    }

    // Verifica se não há erros de entrada antes de inserir no banco de dados
    if (empty($nome_err) && empty($quantidade_err) && empty($quantidade_minima_err) && empty($preco_err)) {
        
        // Prepara uma declaração de inserção
        $sql = "INSERT INTO produtos (nome, descricao, quantidade, quantidade_minima, preco) VALUES (:nome, :descricao, :quantidade, :quantidade_minima, :preco)";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":nome", $nome, PDO::PARAM_STR);
            $stmt->bindParam(":descricao", $descricao, PDO::PARAM_STR);
            $stmt->bindParam(":quantidade", $quantidade, PDO::PARAM_INT);
            $stmt->bindParam(":quantidade_minima", $quantidade_minima, PDO::PARAM_INT);
            $stmt->bindParam(":preco", $preco, PDO::PARAM_STR); // PDO::PARAM_STR para evitar problemas com locale

            // Tenta executar a declaração preparada
            if ($stmt->execute()) {
                // Armazena uma mensagem de sucesso na sessão para ser exibida na próxima página
                $_SESSION['message'] = "Produto '".htmlspecialchars($nome)."' cadastrado com sucesso!";
                // Redireciona para a página de listagem de produtos
                header("location: products.php");
                exit();
            } else {
                echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }
}
?>