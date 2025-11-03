<?php
require 'db.php';
require 'header.php';
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
$stmt = $mysqli->prepare("SELECT c.id_cidade, c.nome as cidade, c.populacao, p.id_pais, p.nome as pais 
FROM cidades c 
JOIN paises p ON c.id_pais = p.id_pais 
ORDER BY c.id_cidade");
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$r['id_cidade']}</td>";
    echo "<td class='city-name'>" . htmlspecialchars($r['cidade']) . "</td>";
    echo "<td>" . number_format($r['populacao'], 0, ',', '.') . "</td>";
    echo "<td><a href='country_view.php?id={$r['id_pais']}'>" . htmlspecialchars($r['pais']) . "</a></td>";
    echo "<td class='weather'>Carregando...</td>";
    echo "<td>
            <a class='small' href='city_edit.php?id={$r['id_cidade']}'>Editar</a> |
            <a class='small danger' href='city_delete.php?id={$r['id_cidade']}' onclick='return confirm(\"Excluir cidade?\");'>Excluir</a>
          </td>";
    echo "</tr>";
}
$stmt->close();
?>
    </tbody>
  </table>
</section>

<script>
const searchCity = document.getElementById('searchCity');
const tableCity = document.getElementById('citiesTable');
searchCity.addEventListener('keyup', function() {
    const term = this.value.toLowerCase();
    const rows = tableCity.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
        const txt = rows[i].innerText.toLowerCase();
        rows[i].style.display = txt.includes(term) ? '' : 'none';
    }
});

// CLIMA 
const apiKey = "9d6ff62e9127dcb0fd3b7966e6e06923";
const weatherCells = document.querySelectorAll(".weather");
const cityNames = document.querySelectorAll(".city-name");

cityNames.forEach((cityEl, i) => {
  const city = cityEl.textContent.trim();
  fetch(`https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(city)}&units=metric&lang=pt_br&appid=${apiKey}`)
    .then(r => r.json())
    .then(data => {
      if (data.main && data.main.temp !== undefined) {
        const temp = Math.round(data.main.temp);
        weatherCells[i].textContent = `${temp}°C`;
      } else {
        weatherCells[i].textContent = "-";
      }
    })
    .catch(() => {
      weatherCells[i].textContent = "-";
    });
});
</script>

<?php require 'footer.php'; ?>
