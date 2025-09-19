<?php
// Este arquivo lida com a lógica de movimentação de estoque.

// Define variáveis e inicializa com valores vazios
$produto_nome = "";
$quantidade_atual = 0;
$tipo = "entrada";
$quantidade = "";
$observacao = "";
$produto_id = 0;

$tipo_err = $quantidade_err = $movimento_err = "";

// Processa os dados do formulário quando o formulário é enviado (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega o ID do campo oculto do formulário
    $produto_id = $_POST["id"];

    // Valida o tipo de movimento
    if (empty($_POST["tipo"]) || !in_array($_POST["tipo"], ['entrada', 'saida'])) {
        $tipo_err = "Por favor, selecione um tipo de movimento válido.";
    } else {
        $tipo = $_POST["tipo"];
    }

    // Valida a quantidade
    if (!isset($_POST["quantidade"]) || $_POST["quantidade"] === '') {
        $quantidade_err = "Por favor, insira a quantidade.";
    } elseif (!ctype_digit($_POST["quantidade"]) || (int)$_POST["quantidade"] <= 0) {
        $quantidade_err = "A quantidade deve ser um número inteiro positivo.";
    } else {
        $quantidade = (int)$_POST["quantidade"];
    }

    // Observação é opcional
    $observacao = trim($_POST["observacao"]);

    // Verifica se não há erros de entrada antes de processar
    if (empty($tipo_err) && empty($quantidade_err)) {
        
        try {
            $pdo->beginTransaction();

            $sql_get_produto = "SELECT nome, quantidade FROM produtos WHERE id = :id FOR UPDATE";
            $stmt_get_produto = $pdo->prepare($sql_get_produto);
            $stmt_get_produto->bindParam(":id", $produto_id, PDO::PARAM_INT);
            $stmt_get_produto->execute();
            
            if ($stmt_get_produto->rowCount() == 1) {
                $produto = $stmt_get_produto->fetch(PDO::FETCH_ASSOC);
                $quantidade_atual = $produto['quantidade'];
                $produto_nome = $produto['nome']; // Get name for display

                if ($tipo === 'saida' && $quantidade > $quantidade_atual) {
                    $movimento_err = "A quantidade de saída ({$quantidade}) não pode ser maior que o estoque atual ({$quantidade_atual}).";
                } else {
                    $nova_quantidade = ($tipo === 'entrada') 
                        ? $quantidade_atual + $quantidade
                        : $quantidade_atual - $quantidade;

                    $sql_update_produto = "UPDATE produtos SET quantidade = :nova_quantidade WHERE id = :id";
                    $stmt_update_produto = $pdo->prepare($sql_update_produto);
                    $stmt_update_produto->bindParam(":nova_quantidade", $nova_quantidade, PDO::PARAM_INT);
                    $stmt_update_produto->bindParam(":id", $produto_id, PDO::PARAM_INT);
                    $stmt_update_produto->execute();

                    $sql_insert_movimento = "INSERT INTO movimentos (produto_id, tipo, quantidade, observacao) VALUES (:produto_id, :tipo, :quantidade, :observacao)";
                    $stmt_insert_movimento = $pdo->prepare($sql_insert_movimento);
                    $stmt_insert_movimento->bindParam(":produto_id", $produto_id, PDO::PARAM_INT);
                    $stmt_insert_movimento->bindParam(":tipo", $tipo, PDO::PARAM_STR);
                    $stmt_insert_movimento->bindParam(":quantidade", $quantidade, PDO::PARAM_INT);
                    $stmt_insert_movimento->bindParam(":observacao", $observacao, PDO::PARAM_STR);
                    $stmt_insert_movimento->execute();

                    $pdo->commit();

                    // Atualiza a variável de quantidade atual para refletir a mudança no banco de dados.
                    // Isso garante que, se o redirecionamento falhar e a página for renderizada,
                    // o valor exibido seja o mais recente.
                    $quantidade_atual = $nova_quantidade;

                    $_SESSION['message'] = "Movimentação de estoque registrada com sucesso!";
                    header("location: products.php");
                    exit();
                }
            } else {
                $movimento_err = "Produto não encontrado.";
            }

        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $movimento_err = "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            // error_log($e->getMessage()); // Log error in production
        }
    }

    // Se chegamos aqui, foi por um erro de validação ou uma exceção capturada.
    // Precisamos buscar novamente os dados do produto para exibir o formulário corretamente.
    if (!empty($movimento_err) || !empty($tipo_err) || !empty($quantidade_err)) {
        $sql = "SELECT nome, quantidade FROM produtos WHERE id = :id";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":id", $produto_id, PDO::PARAM_INT);
            if ($stmt->execute() && $stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $produto_nome = $row["nome"];
                $quantidade_atual = $row["quantidade"];
            }
        }
    }
} else { // GET request
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $produto_id = trim($_GET["id"]);

        $sql = "SELECT id, nome, quantidade FROM produtos WHERE id = :id";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":id", $produto_id, PDO::PARAM_INT);

            if ($stmt->execute() && $stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $produto_nome = $row["nome"];
                $quantidade_atual = $row["quantidade"];
            } else {
                $_SESSION['message_error'] = "Produto não encontrado.";
                header("location: products.php");
                exit();
            }
            unset($stmt);
        }
    } else {
        $_SESSION['message_error'] = "ID do produto não especificado.";
        header("location: products.php");
        exit();
    }
}
?>