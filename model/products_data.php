<?php
// Este arquivo busca os dados para a página de listagem de produtos.
// Ele deve ser incluído em uma página que já tenha iniciado a sessão e incluído o config.php.

// Define o termo de busca a partir do GET, se existir
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Busca os produtos do banco de dados
$produtos = [];
try {
    // A base da consulta SQL
    $sql = "SELECT id, nome, quantidade, quantidade_minima, preco FROM produtos";

    // Se houver um termo de busca, adiciona a cláusula WHERE para filtrar pelo nome
    if (!empty($search_term)) {
        $sql .= " WHERE nome LIKE :search";
    }

    // Adiciona a ordenação
    $sql .= " ORDER BY nome ASC";

    $stmt = $pdo->prepare($sql);

    // Se houver um termo de busca, vincula o parâmetro de forma segura
    if (!empty($search_term)) {
        $search_param = "%" . $search_term . "%";
        $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
    }
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Em um ambiente de produção, seria ideal logar o erro: error_log($e->getMessage());
    // Para o usuário, podemos mostrar uma mensagem amigável
    $error_message = "Oops! Algo deu errado ao carregar os produtos. Tente novamente mais tarde.";
}
?>