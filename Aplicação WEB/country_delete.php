<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: countries.php?msg=" . urlencode("ID inválido."));
    exit;
}

// verificar se existem cidades vinculadas
$stmt = $mysqli->prepare("SELECT COUNT(*) as c FROM cidades WHERE id_pais = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['c'] ?? 0;

if ($count > 0) {
    // não permite excluir
    header("Location: countries.php?msg=" . urlencode("Não é possível excluir: existem cidades vinculadas a este país."));
    exit;
}

// excluir
$del = $mysqli->prepare("DELETE FROM paises WHERE id_pais = ?");
$del->bind_param("i", $id);
if ($del->execute()) {
    header("Location: countries.php?msg=" . urlencode("País excluído."));
    exit;
} else {
    header("Location: countries.php?msg=" . urlencode("Erro ao excluir: " . $mysqli->error));
    exit;
}
