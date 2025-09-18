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
// Inclui o arquivo com a lógica para buscar os dados do dashboard
require_once "model/dashboard_data.php";
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
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