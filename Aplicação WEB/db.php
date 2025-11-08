<?php
// Define as credenciais e informações de conexão com o banco de dados.
$DB_HOST = 'localhost';  // Endereço do servidor MySQL
$DB_USER = 'root';       // Usuário padrão do MySQL em ambiente local
$DB_PASS = '';           // Senha do usuário (geralmente vazia localmente)
$DB_NAME = 'bd_mundo';   // Nome do banco de dados usado pelo sistema

// Cria uma conexão com o banco de dados usando a extensão MySQLi.
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Verifica se ocorreu algum erro na tentativa de conexão.
if ($mysqli->connect_errno) {
    // Encerra a execução do script com uma mensagem detalhada de erro.
    die("Falha na conexão com o banco de dados: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

// Define o conjunto de caracteres para UTF-8, garantindo suporte a acentuação.
$mysqli->set_charset("utf8mb4");
