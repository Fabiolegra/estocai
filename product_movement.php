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

// Inclui o arquivo com a lógica de validação e processamento
require_once "validators/movement_validator.php";
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Movimentar Estoque - estocAI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/movement.css">
</head>
<body>
    <div class="wrapper">
        <h2>Movimentar Estoque do Produto</h2>
        <p>Produto: <strong><?php echo htmlspecialchars($produto_nome); ?></strong></p>
        <p>Estoque Atual: <strong><?php echo htmlspecialchars($quantidade_atual); ?></strong></p>
        <hr>
        <p>Preencha o formulário para registrar uma entrada ou saída de estoque.</p>

        <?php 
        if(!empty($movimento_err)){
            echo '<div class="alert alert-danger">' . htmlspecialchars($movimento_err) . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $produto_id; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $produto_id; ?>">

            <div class="form-group <?php echo (!empty($tipo_err)) ? 'has-error' : ''; ?>">
                <label>Tipo de Movimento</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="tipo" value="entrada" <?php echo ($tipo === 'entrada') ? 'checked' : ''; ?>> Entrada
                    </label>
                    <label>
                        <input type="radio" name="tipo" value="saida" <?php echo ($tipo === 'saida') ? 'checked' : ''; ?>> Saída
                    </label>
                </div>
                <span class="help-block"><?php echo $tipo_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($quantidade_err)) ? 'has-error' : ''; ?>">
                <label>Quantidade</label>
                <input type="number" name="quantidade" class="form-control" value="<?php echo htmlspecialchars($quantidade); ?>" min="1">
                <span class="help-block"><?php echo $quantidade_err; ?></span>
            </div>

            <div class="form-group">
                <label>Observação (Opcional)</label>
                <textarea name="observacao" class="form-control"><?php echo htmlspecialchars($observacao); ?></textarea>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Registrar Movimento">
                <a href="products.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>