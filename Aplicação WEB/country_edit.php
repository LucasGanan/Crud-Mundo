<?php
// Conexão com DB
require 'db.php';

// Obtém o ID do país pela query string e converte para inteiro
$id = intval($_GET['id'] ?? 0);

// Se o id for inválido, redireciona para a lista com mensagem
if ($id <= 0) {
    header("Location: countries.php?msg=" . urlencode("ID inválido."));
    exit;
}

// Se o formulário foi enviado via POST, processa a atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitize dos campos recebidos do formulário
    $nome = trim($_POST['nome'] ?? '');
    $continente = trim($_POST['continente'] ?? '');
    $populacao = intval($_POST['populacao'] ?? 0);
    $idioma = trim($_POST['idioma'] ?? '');

    // Validação simples: todos os campos obrigatórios devem estar preenchidos e população > 0
    if ($nome === '' || $continente === '' || $idioma === '' || $populacao <= 0) {
        $error = "Preencha todos os campos corretamente.";
    } else {
        // Prepara a query de update com parâmetros para evitar SQL injection
        $stmt = $mysqli->prepare("UPDATE paises SET nome=?, continente=?, populacao=?, idioma=? WHERE id_pais=?");
        // Liga os parâmetros (ssisi = string, string, integer, string, integer)
        $stmt->bind_param("ssisi", $nome, $continente, $populacao, $idioma, $id);
        if ($stmt->execute()) {
            // Se atualização bem-sucedida, redireciona para a lista com mensagem de sucesso
            header("Location: countries.php?msg=" . urlencode("País atualizado."));
            exit;
        } else {
            // Se ocorrer erro, armazena a mensagem de erro para exibir na tela
            $error = "Erro ao atualizar: " . $mysqli->error;
        }
    }
}

// ===== Carregar dados atuais do país para preencher o formulário =====
$stmt = $mysqli->prepare("SELECT nome, continente, populacao, idioma FROM paises WHERE id_pais = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

// Se não existir o país, redireciona de volta
if ($res->num_rows === 0) {
    header("Location: countries.php?msg=" . urlencode("País não encontrado."));
    exit;
}
$data = $res->fetch_assoc();

// Inclui o header HTML
require 'header.php';
?>
<section>
  <h2>Editar País</h2>
  <?php if (!empty($error)) echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; ?>
  <form method="post" onsubmit="return validateCountryForm(this);">
    <label>Nome oficial
      <input name="nome" type="text" required maxlength="100" value="<?php echo htmlspecialchars($data['nome']); ?>">
    </label>
    <label>Continente
      <input name="continente" type="text" required maxlength="50" value="<?php echo htmlspecialchars($data['continente']); ?>">
    </label>
    <label>População
      <input name="populacao" type="number" required min="1" step="1" value="<?php echo htmlspecialchars($data['populacao']); ?>">
    </label>
    <label>Idioma principal
      <input name="idioma" type="text" required maxlength="50" value="<?php echo htmlspecialchars($data['idioma']); ?>">
    </label>
    <div class="form-actions">
      <button class="btn">Salvar</button>
      <a class="btn secondary" href="countries.php">Cancelar</a>
    </div>
  </form>
</section>
<?php require 'footer.php'; ?>
