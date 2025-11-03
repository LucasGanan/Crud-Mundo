<?php
require 'db.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: cities.php?msg=" . urlencode("ID inválido."));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $populacao = intval($_POST['populacao'] ?? 0);
    $id_pais = intval($_POST['id_pais'] ?? 0);

    if ($nome === '' || $populacao <= 0 || $id_pais <= 0) {
        $error = "Preencha todos os campos corretamente.";
    } else {
        $stmt = $mysqli->prepare("UPDATE cidades SET nome=?, populacao=?, id_pais=? WHERE id_cidade=?");
        $stmt->bind_param("siii", $nome, $populacao, $id_pais, $id);
        if ($stmt->execute()) {
            header("Location: cities.php?msg=" . urlencode("Cidade atualizada."));
            exit;
        } else {
            $error = "Erro ao atualizar: " . $mysqli->error;
        }
    }
}

// carregar cidade
$stmt = $mysqli->prepare("SELECT nome, populacao, id_pais FROM cidades WHERE id_cidade = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) {
    header("Location: cities.php?msg=" . urlencode("Cidade não encontrada."));
    exit;
}

// países para select
$ps = $mysqli->query("SELECT id_pais, nome FROM paises ORDER BY nome");

require 'header.php';
?>
<section>
  <h2>Editar Cidade</h2>
  <?php if (!empty($error)) echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; ?>
  <form method="post" onsubmit="return validateCityForm(this);">
    <label>Nome da cidade
      <input name="nome" type="text" required maxlength="100" value="<?php echo htmlspecialchars($data['nome']); ?>">
    </label>
    <label>População
      <input name="populacao" type="number" required min="1" step="1" value="<?php echo htmlspecialchars($data['populacao']); ?>">
    </label>
    <label>País
      <select name="id_pais" required>
        <option value="">-- selecione --</option>
        <?php while ($p = $ps->fetch_assoc()) {
            $sel = ($data['id_pais'] == $p['id_pais']) ? 'selected' : '';
            echo "<option value='{$p['id_pais']}' $sel>" . htmlspecialchars($p['nome']) . "</option>";
        } ?>
      </select>
    </label>
    <div class="form-actions">
      <button class="btn">Salvar</button>
      <a class="btn secondary" href="cities.php">Cancelar</a>
    </div>
  </form>
</section>
<?php require 'footer.php'; ?>
