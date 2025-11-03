<?php
require 'db.php';

$country_id = intval($_GET['country_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $populacao = intval($_POST['populacao'] ?? 0);
    $id_pais = intval($_POST['id_pais'] ?? 0);

    if ($nome === '' || $populacao <= 0 || $id_pais <= 0) {
        $error = "Preencha todos os campos corretamente.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO cidades (nome, populacao, id_pais) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $nome, $populacao, $id_pais);
        if ($stmt->execute()) {
            header("Location: cities.php?msg=" . urlencode("Cidade criada."));
            exit;
        } else {
            $error = "Erro ao inserir: " . $mysqli->error;
        }
    }
}

require 'header.php';

// buscar países para select
$ps = $mysqli->query("SELECT id_pais, nome FROM paises ORDER BY nome");
?>
<section>
  <h2>Nova Cidade</h2>
  <?php if (!empty($error)) echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; ?>
  <form method="post" onsubmit="return validateCityForm(this);">
    <label>Nome da cidade
      <input name="nome" type="text" required maxlength="100">
    </label>
    <label>População
      <input name="populacao" type="number" required min="1" step="1">
    </label>
    <label>País
      <select name="id_pais" required>
        <option value="">-- selecione --</option>
        <?php while ($p = $ps->fetch_assoc()) {
            $sel = ($country_id && $country_id == $p['id_pais']) ? 'selected' : '';
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
