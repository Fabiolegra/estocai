<?php
// Inicializa a sessão
session_start();
 
// Verifica se o usuário está logado, se não, redireciona para a página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração para acesso ao banco de dados
require_once "config.php";

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
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Reset de estilos do style.css para um layout de página inteira */
        body {
            display: block;
            justify-content: normal;
            align-items: normal;
            min-height: 100vh;
            padding: 0;
            margin: 0;
            background-color: #f4f7f6;
        }
        .wrapper {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            background-color: transparent;
            box-shadow: none;
            border-radius: 0;
        }

        /* Estilos do Dashboard */
        .header {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .header h1 { margin: 0; font-size: 1.5rem; }
        .header div { display: flex; align-items: center; gap: 1rem; }

        /* Cards de estatísticas (Mobile-first: empilhados verticalmente) */
        .stats-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .stat-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stat-card h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #555;
        }
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .stat-card small { color: #6c757d; }

        /* Ações rápidas */
        .actions-container {
            text-align: center;
            padding: 2rem 1rem;
            margin-top: 2rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .actions-container .btn {
            margin: 0.5rem;
        }

        /* Tabela de produtos recentes */
        .recent-products-container {
            margin-top: 2rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
        }
        .recent-products-container h2 { text-align: left; }
        .table-responsive {
            overflow-x: auto; /* Permite rolagem horizontal em telas pequenas */
        }
        table {
            width: 100%;
            min-width: 600px; /* Força a rolagem em telas menores que 600px */
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        thead th { background-color: #f8f9fa; }
        tbody tr:last-of-type td { border-bottom: 0; }

        /* Media Query para telas maiores (tablets e desktops) */
        @media (min-width: 768px) {
            .stats-container {
                flex-direction: row; /* Organiza os cards em linha */
            }
            .stat-card {
                flex: 1; /* Faz com que os cards ocupem o espaço disponível igualmente */
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard estocAI</h1>
        <div class="user-info">
            <span>Olá, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>.</span>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>

    <div class="wrapper">
        <h2>Visão Geral do Estoque</h2>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total de Produtos</h3>
                <p class="number" style="color: #007bff;"><?php echo $total_produtos; ?></p>
                <small>Tipos de produtos cadastrados</small>
            </div>
            <div class="stat-card">
                <h3>Estoque Crítico</h3>
                <p class="number" style="color: <?php echo ($produtos_criticos > 0) ? '#dc3545' : '#28a745'; ?>;">
                    <?php echo $produtos_criticos; ?>
                </p>
                <small>Abaixo ou igual ao estoque mínimo</small>
            </div>
            <div class="stat-card">
                <h3>Estoque Baixo</h3>
                <p class="number" style="color: <?php echo ($produtos_baixo_estoque > 0) ? '#ffc107' : '#28a745'; ?>;">
                    <?php echo $produtos_baixo_estoque; ?>
                </p>
                <small>Próximo ao estoque mínimo</small>
            </div>
            <div class="stat-card">
                <h3>Itens em Estoque</h3>
                <p class="number" style="color: #17a2b8;"><?php echo number_format($total_itens_estoque, 0, ',', '.'); ?></p>
                <small>Total de unidades no estoque</small>
            </div>
        </div>
        <div class="actions-container">
            <h2>Ações Rápidas</h2>
            <a href="products.php" class="btn btn-secondary">Listar Produtos</a>
            <a href="product_create.php" class="btn btn-primary">Cadastrar Novo Produto</a>
        </div>
        <div class="recent-products-container">
            <h2>Produtos Adicionados Recentemente</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Qtd.</th>
                            <th>Preço (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produtos_recentes)): ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Nenhum produto encontrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($produtos_recentes as $produto): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($produto['id']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($produto['quantidade']); ?></td>
                                    <td><?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</body>
</html>