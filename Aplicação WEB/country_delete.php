<?php
require 'db.php';

// Pega id do país (query string)
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: countries.php?msg=" . urlencode("ID inválido."));
    exit;
}

// ===== Verifica existência de cidades vinculadas =====
// Prepara query para contar quantas cidades referenciam esse país
$stmt = $mysqli->prepare("SELECT COUNT(*) as c FROM cidades WHERE id_pais = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['c'] ?? 0;

// Se houver uma ou mais cidades, não permite exclusão — mantém integridade referencial
if ($count > 0) {
    header("Location: countries.php?msg=" . urlencode("Não é possível excluir: existem cidades vinculadas a este país."));
    exit;
}

// ===== Executa exclusão =====
$del = $mysqli->prepare("DELETE FROM paises WHERE id_pais = ?");
$del->bind_param("i", $id);
if ($del->execute()) {
    header("Location: countries.php?msg=" . urlencode("País excluído."));
    exit;
} else {
    header("Location: countries.php?msg=" . urlencode("Erro ao excluir: " . $mysqli->error));
    exit;
}
