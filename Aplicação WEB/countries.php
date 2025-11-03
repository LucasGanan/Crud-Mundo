<?php
require 'db.php';
require 'header.php';
?>
<section>
  <h2>Países</h2>
    <a class="btn" href="country_create.php">+ Novo País</a>

    <input type="text" id="searchInput" placeholder=" Pesquisar país..." style="margin:15px 0;padding:8px;width:250px;border-radius:6px;border:1px solid #ccc;">

  <table class="fulltable" id="countriesTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nome oficial</th>
        <th>Continente</th>
        <th>População</th>
        <th>Idioma</th>
        <th>Cidades</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
<?php
$stmt = $mysqli->prepare("SELECT id_pais, nome, continente, populacao, idioma FROM paises ORDER BY id_pais");
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $id = $row['id_pais'];
    // contar cidades
    $cstmt = $mysqli->prepare("SELECT COUNT(*) as c FROM cidades WHERE id_pais = ?");
    $cstmt->bind_param("i", $id);
    $cstmt->execute();
    $crow = $cstmt->get_result()->fetch_assoc();
    $countCities = $crow['c'] ?? 0;
    echo "<tr>";
    echo "<td>{$id}</td>";
    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
    echo "<td>" . htmlspecialchars($row['continente']) . "</td>";
    echo "<td>" . number_format($row['populacao'], 0, ',', '.') . "</td>";
    echo "<td>" . htmlspecialchars($row['idioma']) . "</td>";
    echo "<td>{$countCities}</td>";
    echo "<td>
            <a class='small' href='country_edit.php?id={$id}'>Editar</a> |
            <a class='small' href='country_view.php?id={$id}'>Ver</a> |
            <a class='small danger' href='country_delete.php?id={$id}' onclick='return confirmDeleteCountry(event, {$id});'>Excluir</a>
          </td>";
    echo "</tr>";
}
$stmt->close();
?>
    </tbody>
  </table>
</section>

<script>
const input = document.getElementById('searchInput');
const table = document.getElementById('countriesTable');
input.addEventListener('keyup', function() {
    const term = this.value.toLowerCase();
    const rows = table.getElementsByTagName('tr');
    for (let i = 1; i < rows.length; i++) {
        const txt = rows[i].innerText.toLowerCase();
        rows[i].style.display = txt.includes(term) ? '' : 'none';
    }
});
</script>


<?php require 'footer.php'; ?>
