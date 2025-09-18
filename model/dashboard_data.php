<?php
// Este arquivo busca os dados para o dashboard.
// Ele deve ser incluído em uma página que já tenha iniciado a sessão e incluído o config.php.

// Inicializa variáveis para as estatísticas
$total_produtos = 0;
$produtos_criticos = 0;
$produtos_baixo_estoque = 0;
$total_itens_estoque = 0;
$produtos_recentes = [];

try {
    // Consulta para obter o total de produtos distintos
    $sql_total_produtos = "SELECT COUNT(id) AS total FROM produtos";
    $stmt_total_produtos = $pdo->query($sql_total_produtos);
    $total_produtos = $stmt_total_produtos->fetchColumn();

    // Consulta para obter o número de produtos com estoque crítico (quantidade <= quantidade_minima)
    $sql_produtos_criticos = "SELECT COUNT(id) AS criticos FROM produtos WHERE quantidade <= quantidade_minima";
    $stmt_produtos_criticos = $pdo->query($sql_produtos_criticos);
    $produtos_criticos = $stmt_produtos_criticos->fetchColumn();

    // Consulta para produtos com estoque baixo (acima do mínimo, mas com margem pequena)
    // Definindo 'baixo' como até 20% acima do mínimo.
    $sql_baixo_estoque = "SELECT COUNT(id) FROM produtos WHERE quantidade > quantidade_minima AND quantidade <= (quantidade_minima * 1.2)";
    $stmt_baixo_estoque = $pdo->query($sql_baixo_estoque);
    $produtos_baixo_estoque = $stmt_baixo_estoque->fetchColumn();

    // Consulta para obter o total de itens em estoque (soma de todas as quantidades)
    $sql_total_itens = "SELECT SUM(quantidade) AS total_itens FROM produtos";
    $stmt_total_itens = $pdo->query($sql_total_itens);
    $total_itens_estoque = $stmt_total_itens->fetch(PDO::FETCH_ASSOC)['total_itens'] ?? 0;

    // Consulta para obter os últimos 5 produtos adicionados
    $sql_produtos_recentes = "SELECT id, nome, quantidade, preco FROM produtos ORDER BY criado_at DESC LIMIT 5";
    $stmt_produtos_recentes = $pdo->query($sql_produtos_recentes);
    $produtos_recentes = $stmt_produtos_recentes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em um ambiente de produção, seria ideal logar o erro: error_log($e->getMessage());
    // Para este exemplo, as variáveis manterão seus valores padrão (0) em caso de falha.
}
?>

