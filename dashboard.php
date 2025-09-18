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
    <link rel="stylesheet" href="css/responsive-table.css">
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
            <div class="product-grid-container">
                <div class="product-grid-header">
                    <div>ID</div>
                    <div>Nome</div>
                    <div>Qtd.</div>
                    <div>Preço (R$)</div>
                </div>
                <div class="product-grid-body">
                    <?php if (empty($produtos_recentes)): ?>
                        <div class="empty-state">Nenhum produto encontrado.</div>
                    <?php else: ?>
                        <?php foreach ($produtos_recentes as $produto): ?>
                            <div class="product-card">
                                <div class="card-item" data-label="ID"><?php echo htmlspecialchars($produto['id']); ?></div>
                                <div class="card-item" data-label="Nome"><?php echo htmlspecialchars($produto['nome']); ?></div>
                                <div class="card-item" data-label="Qtd."><?php echo htmlspecialchars($produto['quantidade']); ?></div>
                                <div class="card-item" data-label="Preço (R$)"><?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</body>
</html>