<?php
// Inicia a sessão
session_start();
 
// Verifica se o usuário está logado, se não, redireciona para a página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração e o validador
require_once "config.php";
require_once "validators/product_validator.php";
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css"> <!-- Reutilizando o CSS de autenticação para formulários -->
    <style>
        /* Pequenos ajustes para a página de produto */
        .wrapper {
            max-width: 700px; /* Um pouco mais largo para o formulário de produto */
        }
        .form-group-half {
            display: flex;
            gap: 1rem;
        }
        .form-group-half .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h2>Cadastrar Novo Produto</h2>
            <a href="dashboard.php" class="btn btn-secondary">Voltar ao Dashboard</a>
        </div>
        <p>Preencha os campos abaixo para adicionar um novo produto ao estoque.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nome do Produto</label>
                <input type="text" name="nome" class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>" placeholder="Ex: Monitor LED 24 polegadas">
                <span class="invalid-feedback"><?php echo $nome_err; ?></span>
            </div>

            <div class="form-group">
                <label>Descrição (Opcional)</label>
                <textarea name="descricao" class="form-control" rows="3" placeholder="Detalhes do produto, como marca, modelo, cor, etc."><?php echo $descricao; ?></textarea>
            </div>

            <div class="form-group-half">
                <div class="form-group">
                    <label>Quantidade em Estoque</label>
                    <input type="number" name="quantidade" min="0" class="form-control <?php echo (!empty($quantidade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantidade; ?>" placeholder="0">
                    <span class="invalid-feedback"><?php echo $quantidade_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Estoque Mínimo</label>
                    <input type="number" name="quantidade_minima" min="0" class="form-control <?php echo (!empty($quantidade_minima_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantidade_minima; ?>" placeholder="0">
                    <span class="invalid-feedback"><?php echo $quantidade_minima_err; ?></span>
                </div>
            </div>

            <div class="form-group">
                <label>Preço (R$)</label>
                <input type="text" name="preco" class="form-control <?php echo (!empty($preco_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $preco; ?>" placeholder="Ex: 1250,99">
                <span class="invalid-feedback"><?php echo $preco_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Cadastrar Produto">
            </div>
        </form>
    </div>
</body>
</html>