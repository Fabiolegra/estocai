<?php
// Este arquivo busca os dados para a página de histórico de movimentações.
// Ele deve ser incluído em uma página que já tenha iniciado a sessão e incluído o config.php.

// Busca as movimentações do banco de dados
$movimentos = [];
$error_message = '';

try {
    // A consulta SQL para buscar as movimentações, juntando com a tabela de produtos para obter o nome
    $sql = "SELECT 
                m.id,
                m.tipo,
                m.quantidade,
                m.observacao,
                m.created_at AS data,
                p.nome AS produto_nome
            FROM 
                movimentos AS m
            LEFT JOIN 
                produtos AS p ON m.produto_id = p.id
            ORDER BY 
                m.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $movimentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em um ambiente de produção, seria ideal logar o erro: error_log($e->getMessage());
    // Para o usuário, podemos mostrar uma mensagem amigável
    $error_message = "Oops! Algo deu errado ao carregar o histórico. Tente novamente mais tarde.";
}
?>