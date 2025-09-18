<?php
// Inicia a sessão
session_start();
 
// Verifica se o usuário está logado, se não, redireciona para a página de login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Inclui o arquivo de configuração e o validador de atualização
require_once "config.php";
require_once "validators/product_update_validator.php";
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto - estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css"> <!-- Reutilizando o CSS de autenticação para formulários -->
    <style>
        /* Pequenos ajustes para a página de produto */
        .wrapper {
            max-width: 700px;
        }
        /* Mobile-first: os campos empilham por padrão. */
        /* Em telas maiores, eles ficam lado a lado. */
        @media (min-width: 576px) {
            .form-group-half {
                display: flex;
                gap: 1rem;
                /* O container precisa de margem para se separar do próximo campo */
                margin-bottom: 20px;
            }
            .form-group-half .form-group {
                flex: 1;
                /* Remove a margem dos filhos para evitar espaçamento duplo */
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h2>Editar Produto</h2>
            <a href="products.php" class="btn btn-secondary">Voltar para a Lista</a>
        </div>
        <p>Altere os campos abaixo e salve para atualizar o produto.</p>

        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group">
                <label>Nome do Produto</label>
                <input type="text" name="nome" class="form-control <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
                <span class="invalid-feedback"><?php echo $nome_err; ?></span>
            </div>

            <div class="form-group">
                <label>Descrição (Opcional)</label>
                <textarea name="descricao" class="form-control" rows="3"><?php echo $descricao; ?></textarea>
            </div>

            <div class="form-group-half">
                <div class="form-group">
                    <label>Quantidade em Estoque</label>
                    <input type="number" name="quantidade" min="0" class="form-control <?php echo (!empty($quantidade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantidade; ?>">
                    <span class="invalid-feedback"><?php echo $quantidade_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Estoque Mínimo</label>
                    <input type="number" name="quantidade_minima" min="0" class="form-control <?php echo (!empty($quantidade_minima_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantidade_minima; ?>">
                    <span class="invalid-feedback"><?php echo $quantidade_minima_err; ?></span>
                </div>
            </div>

            <div class="form-group">
                <label>Preço (R$)</label>
                <input type="text" name="preco" class="form-control <?php echo (!empty($preco_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $preco; ?>">
                <span class="invalid-feedback"><?php echo $preco_err; ?></span>
            </div>

            <input type="hidden" name="id" value="<?php echo $id; ?>"/>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Salvar Alterações">
            </div>
        </form>
    </div>
</body>
</html>