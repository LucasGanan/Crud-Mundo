<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $continente = trim($_POST['continente'] ?? '');
    $populacao = intval($_POST['populacao'] ?? 0);
    $idioma = trim($_POST['idioma'] ?? '');

    if ($nome === '' || $continente === '' || $idioma === '' || $populacao <= 0) {
        $error = "Preencha todos os campos corretamente.";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO paises (nome, continente, populacao, idioma) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $nome, $continente, $populacao, $idioma);
        if ($stmt->execute()) {
            header("Location: countries.php?msg=" . urlencode("País criado com sucesso."));
            exit;
        } else {
            $error = "Erro ao inserir: " . $mysqli->error;
        }
    }
}

require 'header.php';
?>
<section>
  <h2>Novo País</h2>
  <?php if (!empty($error)) echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; ?>
  <form method="post" onsubmit="return validateCountryForm(this);">
    <label>Nome oficial
      <input name="nome" type="text" required maxlength="100">
    </label>
    <label>Continente
      <input name="continente" type="text" required maxlength="50">
    </label>
    <label>População
      <input name="populacao" type="number" required min="1" step="1">
    </label>
    <label>Idioma principal
      <input name="idioma" type="text" required maxlength="50">
    </label>
    <div class="form-actions">
      <button class="btn">Salvar</button>
      <a class="btn secondary" href="countries.php">Cancelar</a>
    </div>
  </form>
</section>
<?php require 'footer.php'; ?>
