<?php
require 'db.php'; // Conecta ao banco de dados

// Captura um ID de país opcional passado pela URL, caso seja criação de cidade para um país específico
$country_id = intval($_GET['country_id'] ?? 0);

// Processa o envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os valores enviados no formulário
    $nome = trim($_POST['nome'] ?? '');
    $populacao = intval($_POST['populacao'] ?? 0);
    $id_pais = intval($_POST['id_pais'] ?? 0);

    // Validação básica — todos os campos obrigatórios e população maior que 0
    if ($nome === '' || $populacao <= 0 || $id_pais <= 0) {
        $error = "Preencha todos os campos corretamente.";
    } else {
        // Prepara a query para inserir a nova cidade
        $stmt = $mysqli->prepare("INSERT INTO cidades (nome, populacao, id_pais) VALUES (?, ?, ?)");
        // Associa os parâmetros da query às variáveis PHP
        $stmt->bind_param("sii", $nome, $populacao, $id_pais);

        // Executa a inserção
        if ($stmt->execute()) {
            // Redireciona para a lista de cidades com mensagem de sucesso
            header("Location: cities.php?msg=" . urlencode("Cidade criada."));
            exit;
        } else {
            // Em caso de erro no banco, guarda a mensagem
            $error = "Erro ao inserir: " . $mysqli->error;
        }
    }
}

// Inclui o cabeçalho padrão do site
require 'header.php';

// Busca todos os países para popular o select do formulário
$ps = $mysqli->query("SELECT id_pais, nome FROM paises ORDER BY nome");
?>
<section>
  <h2>Nova Cidade</h2>

  <?php 
  // Se houver erro de validação ou inserção, exibe ao usuário
  if (!empty($error)) 
      echo "<div class='error'>" . htmlspecialchars($error) . "</div>"; 
  ?>

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
        <?php 
        // Preenche o select com todos os países
        while ($p = $ps->fetch_assoc()) {
            // Marca como "selecionado" se o ID do país vier da URL
            $sel = ($country_id && $country_id == $p['id_pais']) ? 'selected' : '';
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
