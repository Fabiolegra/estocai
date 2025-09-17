<?php
/*
 * Arquivo de configuração do banco de dados.
 * Este arquivo contém as credenciais para acessar o banco de dados.
 */

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Usuário padrão do XAMPP
define('DB_PASSWORD', '');     // Senha padrão do XAMPP é vazia
define('DB_NAME', 'estocaidb'); // Nome do seu banco de dados, conforme o schema.sql

/* Tentativa de conexão com o banco de dados MySQL usando PDO */
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    // Define o modo de erro do PDO para exceção, para que os erros sejam mostrados
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    // Se a conexão falhar, exibe uma mensagem de erro e interrompe o script
    die("ERRO: Não foi possível conectar ao banco de dados. " . $e->getMessage());
}
?>
