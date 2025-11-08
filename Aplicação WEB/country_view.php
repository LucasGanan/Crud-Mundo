<?php
// Conexão com o banco e header (HTML inicial)
require 'db.php';
require 'header.php';

// Pega o parâmetro 'id' da URL e transforma em inteiro seguro.
// Se não existir, recebe 0 por padrão.
$id = intval($_GET['id'] ?? 0);

// Se o id for inválido (<= 0), redireciona de volta para a lista de países com mensagem.
if ($id <= 0) {
    header("Location: countries.php?msg=" . urlencode("ID inválido."));
    exit;
}

// ===== Buscar país no banco =====
// Prepara a query para evitar SQL injection.
// Seleciona as colunas necessárias da tabela paises onde id_pais = ?
$stmt = $mysqli->prepare("SELECT id_pais, nome, continente, populacao, idioma FROM paises WHERE id_pais = ?");
$stmt->bind_param("i", $id); // liga o parâmetro (i = integer)
$stmt->execute();
$country = $stmt->get_result()->fetch_assoc(); // obtém a linha como array associativo

// Se não existe país com esse id, redireciona mostrando mensagem.
if (!$country) {
    header("Location: countries.php?msg=" . urlencode("País não encontrado."));
    exit;
}

// ===== Integração com REST COUNTRIES API =====
// Monta a URL de consulta usando o endpoint de tradução (aceita nomes em PT)
$api_url = "https://restcountries.com/v3.1/translation/" . urlencode($country['nome']);
$api_data = null;

// Usa cURL para fazer a requisição HTTP para a API externa
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Se a API retornou algo, tenta decodificar o JSON
if ($response) {
    $data = json_decode($response, true);
    // As respostas da API normalmente vêm como um array; pegamos o primeiro elemento se existir
    if (is_array($data) && isset($data[0])) {
        $api_data = $data[0];
    }
}
?>
<section>
  <h2>País: <?php echo htmlspecialchars($country['nome']); ?></h2>

  <?php if ($api_data && isset($api_data['flags']['png'])): ?>
    <img src="<?php echo htmlspecialchars($api_data['flags']['png']); ?>" alt="Bandeira de <?php echo htmlspecialchars($country['nome']); ?>" style="width:160px;border:1px solid #ddd;border-radius:6px;">
  <?php endif; ?>

  <dl class="details">
    <dt>Continente</dt><dd><?php echo htmlspecialchars($country['continente']); ?></dd>
    <dt>População</dt><dd><?php echo number_format($country['populacao'], 0, ',', '.'); ?></dd>
    <dt>Idioma</dt><dd><?php echo htmlspecialchars($country['idioma']); ?></dd>

    <?php if ($api_data): ?>
      <?php if (!empty($api_data['capital'][0])): ?>
        <dt>Capital</dt><dd><?php echo htmlspecialchars($api_data['capital'][0]); ?></dd>
      <?php endif; ?>
      <?php if (!empty($api_data['currencies'])): 
            // pega a primeira moeda do array 'currencies' retornado pela API
            $currency = reset($api_data['currencies']);
            $currency_name = $currency['name'] ?? '';
            $currency_symbol = $currency['symbol'] ?? '';
      ?>
        <dt>Moeda</dt><dd><?php echo htmlspecialchars($currency_name . " (" . $currency_symbol . ")"); ?></dd>
      <?php endif; ?>
    <?php endif; ?>
  </dl>

  <h3>Cidades deste país</h3>
  <a class="btn" href="city_create.php?country_id=<?php echo $country['id_pais']; ?>">+ Nova cidade neste país</a>
  <table class="fulltable">
    <thead><tr><th>ID</th><th>Nome</th><th>População</th><th>Clima</th><th>Ações</th></tr></thead>
    <tbody>
<?php
// ===== Buscar cidades do país =====
// Prepara uma query para buscar as cidades relacionadas ao país, ordenadas por id_cidade
$cstmt = $mysqli->prepare("SELECT id_cidade, nome, populacao FROM cidades WHERE id_pais = ? ORDER BY id_cidade");
$cstmt->bind_param("i", $id);
$cstmt->execute();
$cres = $cstmt->get_result();

// ===== Integração com OpenWeatherMap para cada cidade =====
// A variável $API_KEY deve conter sua chave da OpenWeatherMap
$API_KEY = "9d6ff62e9127dcb0fd3b7966e6e06923"; 

while ($crow = $cres->fetch_assoc()) {
    $city_name = $crow['nome'];

    // Monta a URL da API de clima com nome da cidade, unidades em metric e idioma pt_br
    $weather_url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city_name) . "&lang=pt_br&units=metric&appid=" . $API_KEY;

    // Faz a requisição com cURL (server-side)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $weather_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $w_response = curl_exec($ch);
    curl_close($ch);

    // Valor padrão caso a API falhe ou não encontre a cidade
    $temp_text = "N/D";

    if ($w_response) {
        $w_data = json_decode($w_response, true);
        // Verifica se a resposta contém a temperatura
        if (isset($w_data['main']['temp'])) {
            $temp_text = round($w_data['main']['temp']) . "°C";
        }
    }

    // Imprime a linha da tabela com dados da cidade e a temperatura (ou N/D)
    echo "<tr>";
    echo "<td>{$crow['id_cidade']}</td>";
    echo "<td>" . htmlspecialchars($crow['nome']) . "</td>";
    echo "<td>" . number_format($crow['populacao'], 0, ',', '.') . "</td>";
    echo "<td>" . htmlspecialchars($temp_text) . "</td>";
    echo "<td>
            <a class='small' href='city_edit.php?id=" . $crow['id_cidade'] . "'>Editar</a> |
            <a class='small danger' href='city_delete.php?id=" . $crow['id_cidade'] . "' onclick='return confirm(\"Excluir cidade?\");'>Excluir</a>
          </td>";
    echo "</tr>";
}
?>
    </tbody>
  </table>
  <p><a class="btn secondary" href="countries.php">Voltar</a></p>
</section>

<?php require 'footer.php'; ?>
