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

// Busca todos os produtos do banco de dados
$produtos = [];
try {
    $sql = "SELECT id, nome, quantidade, quantidade_minima, preco FROM produtos ORDER BY nome ASC";
    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Em um ambiente de produção, seria ideal logar o erro
    // error_log("Erro ao buscar produtos: " . $e->getMessage());
    // Para o usuário, podemos mostrar uma mensagem amigável
    $error_message = "Oops! Algo deu errado ao carregar os produtos. Tente novamente mais tarde.";
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos - estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Reutiliza estilos do header -->
    <link rel="stylesheet" href="css/products.css"> <!-- Estilos específicos da página -->
    <link rel="stylesheet" href="css/responsive-table.css">
</head>
<body>
    <div class="header">
        <h1>Gerenciar Produtos</h1>
        <div class="user-info">
            <span>Olá, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>.</span>
            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>
    </div>

    <div class="wrapper">
        <div class="page-header">
            <h2>Lista de Produtos</h2>
            <div>
                <a href="product_create.php" class="btn btn-primary">Adicionar Novo Produto</a>
                <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
            </div>
        </div>

        <?php 
        // Exibe a mensagem de sucesso, se houver
        if(isset($_SESSION['message'])){
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']); // Limpa a mensagem da sessão
        }
        // Exibe a mensagem de erro, se houver
        if(isset($_SESSION['message_error'])){
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['message_error']) . '</div>';
            unset($_SESSION['message_error']); // Limpa a mensagem da sessão
        }
        // Exibe mensagem de erro na busca
        if(isset($error_message)){
            echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
        }
        ?>

        <div class="product-grid-container">
            <div class="product-grid-header">
                <div>ID</div>
                <div>Nome</div>
                <div>Quantidade</div>
                <div>Preço (R$)</div>
                <div>Ações</div>
            </div>
            <div class="product-grid-body">
                <?php if (empty($produtos)): ?>
                    <div class="empty-state">Nenhum produto cadastrado.</div>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="product-card <?php echo ($produto['quantidade'] <= $produto['quantidade_minima']) ? 'row-critical' : ''; ?>">
                            <div class="card-item" data-label="ID"><?php echo htmlspecialchars($produto['id']); ?></div>
                            <div class="card-item" data-label="Nome"><?php echo htmlspecialchars($produto['nome']); ?></div>
                            <div class="card-item" data-label="Quantidade"><?php echo htmlspecialchars($produto['quantidade']); ?></div>
                            <div class="card-item" data-label="Preço (R$)"><?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                            <div class="card-item actions" data-label="Ações">
                                <a href="product_edit.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="product_delete.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>