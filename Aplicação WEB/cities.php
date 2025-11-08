<?php
require 'db.php'; // Conecta ao banco de dados
require 'header.php'; // Inclui o cabeçalho padrão
?>
<section>
  <h2>Cidades</h2>
  <a class="btn" href="city_create.php">+ Nova cidade</a>
  <input type="text" id="searchCity" placeholder="Pesquisar cidade..." style="margin:15px 0;padding:8px;width:250px;border-radius:6px;border:1px solid #ccc;">

  <table class="fulltable" id="citiesTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>População</th>
        <th>País</th>
        <th>Clima</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
<?php
// Prepara uma query que busca todas as cidades junto com o nome e ID do país correspondente
$stmt = $mysqli->prepare("SELECT c.id_cidade, c.nome as cidade, c.populacao, p.id_pais, p.nome as pais 
FROM cidades c 
JOIN paises p ON c.id_pais = p.id_pais 
ORDER BY c.id_cidade");
$stmt->execute();
$res = $stmt->get_result(); // Recupera os resultados da query

// Itera por cada cidade retornada do banco
while ($r = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$r['id_cidade']}</td>";
    echo "<td class='city-name'>" . htmlspecialchars($r['cidade']) . "</td>";
    echo "<td>" . number_format($r['populacao'], 0, ',', '.') . "</td>";
    // Link para visualizar país da cidade
    echo "<td><a href='country_view.php?id={$r['id_pais']}'>" . htmlspecialchars($r['pais']) . "</a></td>";
    echo "<td class='weather'>Carregando...</td>"; // Campo que será atualizado via JS com clima
    echo "<td>
            <a class='small' href='city_edit.php?id={$r['id_cidade']}'>Editar</a> |
            <a class='small danger' href='city_delete.php?id={$r['id_cidade']}' onclick='return confirm(\"Excluir cidade?\");'>Excluir</a>
          </td>";
    echo "</tr>";
}
$stmt->close(); // Fecha a query
?>
    </tbody>
  </table>
</section>

<script>
// --- Filtro de pesquisa ---
const searchCity = document.getElementById('searchCity');
const tableCity = document.getElementById('citiesTable');

searchCity.addEventListener('keyup', function() {
    const term = this.value.toLowerCase(); // converte texto pesquisado para minúsculo
    const rows = tableCity.getElementsByTagName('tr'); // pega todas as linhas da tabela
    for (let i = 1; i < rows.length; i++) { // começa em 1 para pular o cabeçalho
        const txt = rows[i].innerText.toLowerCase(); // pega o texto da linha
        rows[i].style.display = txt.includes(term) ? '' : 'none'; // esconde ou mostra
    }
});

// --- CLIMA ---
const apiKey = "9d6ff62e9127dcb0fd3b7966e6e06923"; // chave da API do OpenWeatherMap
const weatherCells = document.querySelectorAll(".weather"); // todas células de clima
const cityNames = document.querySelectorAll(".city-name"); // todas células de nomes de cidade

// Para cada cidade, faz requisição à API do clima
cityNames.forEach((cityEl, i) => {
  const city = cityEl.textContent.trim();
  fetch(`https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(city)}&units=metric&lang=pt_br&appid=${apiKey}`)
    .then(r => r.json())
    .then(data => {
      if (data.main && data.main.temp !== undefined) {
        const temp = Math.round(data.main.temp); // arredonda temperatura
        weatherCells[i].textContent = `${temp}°C`; // atualiza célula da tabela
      } else {
        weatherCells[i].textContent = "-"; // caso não tenha dados
      }
    })
    .catch(() => {
      weatherCells[i].textContent = "-"; // caso ocorra erro na requisição
    });
});
</script>

<?php require 'footer.php'; ?>
