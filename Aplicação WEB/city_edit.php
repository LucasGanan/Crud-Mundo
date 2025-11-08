<?php
require 'db.php'; // Conecta ao banco de dados (arquivo central de conexão)

// Captura o ID enviado pela URL e garante que é um número inteiro
$id = intval($_GET['id'] ?? 0);

// Se o ID for inválido (zero ou negativo), redireciona de volta à lista de cidades
if ($id <= 0) {
    header("Location: cities.php?msg=" . urlencode("ID inválido."));
    exit;
}

// Se o formulário foi enviado (requisição POST), processa os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os valores enviados no formulário
    $nome = trim($_POST['nome'] ?? '');
    $populacao = intval($_POST['populacao'] ?? 0);
    $id_pais = intval($_POST['id_pais'] ?? 0);

    // Validação básica — garante que todos os campos foram preenchidos corretamente
    if ($nome === '' || $populacao <= 0 || $id_pais <= 0) {
        $error = "Preencha todos os campos corretamente.";
    } else {
        // Prepara o comando SQL para atualizar os dados da cidade
        $stmt = $mysqli->prepare("UPDATE cidades SET nome=?, populacao=?, id_pais=? WHERE id_cidade=?");
        // Liga as variáveis aos parâmetros da query
        $stmt->bind_param("siii", $nome, $populacao, $id_pais, $id);

        // Executa a query e verifica se deu certo
        if ($stmt->execute()) {
            // Redireciona de volta à página de listagem com mensagem de sucesso
            header("Location: cities.php?msg=" . urlencode("Cidade atualizada."));
            exit;
        } else {
            // Caso ocorra erro no banco, guarda a mensagem
            $error = "Erro ao atualizar: " . $mysqli->error;
        }
    }
}

// Caso a página tenha sido aberta sem envio de formulário (GET),
// buscamos os dados atuais da cidade para preencher o formulário
$stmt = $mysqli->prepare("SELECT nome, populacao, id_pais FROM cidades WHERE id_cidade = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// Se não encontrar a cidade, redireciona de volta à lista
if (!$data) {
    header("Location: cities.php?msg=" . urlencode("Cidade não encontrada."));
    exit;
}

// Busca todos os países para preencher o select do formulário
$ps = $mysqli->query("SELECT id_pais, nome FROM paises ORDER BY nome");

// Inclui o cabeçalho padrão do site
require 'header.php';
?>
<section>
  <h2>Editar Cidade</h2>

  <?php 
  // Caso exista erro (exemplo: campos inválidos), exibe na tela
  if (!empty($error)) 
      echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; 
  ?>

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
        <?php 
        // Gera cada opção da lista de países
        while ($p = $ps->fetch_assoc()) {
            // Marca como "selecionado" o país da cidade atual
            $sel = ($data['id_pais'] == $p['id_pais']) ? 'selected' : '';
            echo "<option value='{$p['id_pais']}' $sel>" . htmlspecialchars($p['nome']) . "</option>";
        } 
        ?>
      </select>
    </label>

    <div class="form-actions">
      <button class="btn">Salvar</button>
      <a class="btn secondary" href="cities.php">Cancelar</a>
    </div>
  </form>
</section>

<?php require 'footer.php'; ?>
