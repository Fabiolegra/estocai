<?php
// Inicia a sessão
session_start();
 
// Verifica se o usuário está logado, se não, redireciona para a página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração
require_once "config.php";

// Inclui o arquivo com a lógica para buscar os dados das movimentações
require_once "model/movements_data.php";
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Movimentações - estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Reutiliza estilos do header e layout -->
    <link rel="stylesheet" href="css/responsive-table.css"> <!-- Reutiliza estilos da tabela responsiva -->
    <link rel="stylesheet" href="css/movements_page.css"> <!-- Estilos específicos -->
</head>
<body>
    <div class="header">
        <h1>Histórico de Movimentações</h1>
        <div class="user-info">
            <span>Olá, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>.</span>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>

    <div class="wrapper">
        <div class="page-header">
            <h2>Todas as Entradas e Saídas</h2>
            <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
        </div>

        <?php if(!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="movement-grid-container">
            <div class="movement-grid-header">
                <div>Produto</div>
                <div>Tipo</div>
                <div>Quantidade</div>
                <div>Data</div>
                <div>Observação</div>
            </div>
            <div class="movement-grid-body">
                <?php if (empty($movimentos)): ?>
                    <div class="empty-state">Nenhuma movimentação registrada.</div>
                <?php else: ?>
                    <?php foreach ($movimentos as $movimento): ?>
                        <div class="movement-card">
                            <div class="card-item" data-label="Produto">
                                <?php echo htmlspecialchars($movimento['produto_nome'] ?? 'Produto Excluído'); ?>
                            </div>
                            <div class="card-item" data-label="Tipo">
                                <span class="badge <?php echo ($movimento['tipo'] == 'entrada') ? 'badge-success' : 'badge-warning'; ?>">
                                    <?php echo ucfirst(htmlspecialchars($movimento['tipo'])); ?>
                                </span>
                            </div>
                            <div class="card-item" data-label="Quantidade"><?php echo htmlspecialchars($movimento['quantidade']); ?></div>
                            <div class="card-item" data-label="Data"><?php echo date("d/m/Y H:i", strtotime($movimento['data'])); ?></div>
                            <div class="card-item" data-label="Observação"><?php echo htmlspecialchars($movimento['observacao']) ?: '-'; ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>