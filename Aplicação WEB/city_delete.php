<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: cities.php?msg=" . urlencode("ID inválido."));
    exit;
}

$del = $mysqli->prepare("DELETE FROM cidades WHERE id_cidade = ?");
$del->bind_param("i", $id);
if ($del->execute()) {
    header("Location: cities.php?msg=" . urlencode("Cidade excluída."));
    exit;
} else {
    header("Location: cities.php?msg=" . urlencode("Erro ao excluir: " . $mysqli->error));
    exit;
}
