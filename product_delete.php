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

// Verifica se o ID do produto foi passado pela URL e é um número
if(isset($_GET["id"]) && !empty(trim($_GET["id"])) && is_numeric($_GET["id"])){
    // Prepara a declaração de exclusão
    $sql = "DELETE FROM produtos WHERE id = :id";
    
    if($stmt = $pdo->prepare($sql)){
        // Vincula o parâmetro
        $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
        
        // Define o parâmetro
        $param_id = trim($_GET["id"]);
        
        // Tenta executar a declaração
        if($stmt->execute()){
            // Verifica se alguma linha foi afetada para confirmar a exclusão
            if($stmt->rowCount() > 0){
                $_SESSION['message'] = "Produto excluído com sucesso.";
            } else {
                // Se nenhuma linha foi afetada, o produto com esse ID não existia
                $_SESSION['message_error'] = "Nenhum produto encontrado com o ID fornecido.";
            }
        } else{
            $_SESSION['message_error'] = "Oops! Algo deu errado. Não foi possível excluir o produto.";
        }
    }
    unset($stmt);
} else {
    // Se nenhum ID válido foi passado, informa um erro
    $_SESSION['message_error'] = "Requisição inválida. ID do produto ausente ou inválido.";
}

// Redireciona de volta para a página de listagem de produtos
header("location: products.php");
exit();
?>