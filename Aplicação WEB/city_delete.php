<?php
require 'db.php'; // Conecta ao banco de dados

// Pega o ID da cidade que queremos excluir da URL e converte para inteiro
$id = intval($_GET['id'] ?? 0);

// Validação básica: se o ID não for válido, redireciona para lista de cidades
if ($id <= 0) {
    header("Location: cities.php?msg=" . urlencode("ID inválido."));
    exit;
}

// Prepara a query de exclusão da cidade
$del = $mysqli->prepare("DELETE FROM cidades WHERE id_cidade = ?");
// Associa o parâmetro da query com o ID capturado
$del->bind_param("i", $id);

// Executa a exclusão
if ($del->execute()) {
    // Se sucesso, redireciona para a lista de cidades com mensagem de sucesso
    header("Location: cities.php?msg=" . urlencode("Cidade excluída."));
    exit;
} else {
    // Se houve erro, redireciona com a mensagem de erro do MySQL
    header("Location: cities.php?msg=" . urlencode("Erro ao excluir: " . $mysqli->error));
    exit;
}
