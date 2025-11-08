<?php
require 'db.php'; // Conecta ao banco de dados
require 'header.php'; // Inclui cabeçalho
?>

<section>
  <h2>Estatísticas do Sistema</h2>

  <h3>Cidade mais populosa por país</h3>
  <table class="fulltable">
    <thead><tr><th>País</th><th>Cidade</th><th>População</th></tr></thead>
    <tbody>
<?php
// Query que seleciona a cidade mais populosa de cada país
$sql = "
  SELECT p.nome AS pais, c.nome AS cidade, c.populacao
  FROM cidades c
  JOIN paises p ON c.id_pais = p.id_pais
  WHERE c.populacao = (
    SELECT MAX(c2.populacao) 
    FROM cidades c2
    WHERE c2.id_pais = p.id_pais
  )
  ORDER BY p.nome
";
$res = $mysqli->query($sql);

// Itera por cada linha retornada e monta a tabela
while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['pais']) . "</td>
            <td>" . htmlspecialchars($row['cidade']) . "</td>
            <td>" . number_format($row['populacao'], 0, ',', '.') . "</td>
          </tr>";
}
?>
    </tbody>
  </table>

  <h3>Total de cidades por continente</h3>
  <table class="fulltable">
    <thead><tr><th>Continente</th><th>Total de Cidades</th></tr></thead>
    <tbody>
<?php
// Query que conta cidades agrupadas por continente
$sql2 = "
  SELECT p.continente, COUNT(c.id_cidade) AS total
  FROM paises p
  LEFT JOIN cidades c ON c.id_pais = p.id_pais
  GROUP BY p.continente
  ORDER BY total DESC
";
$res2 = $mysqli->query($sql2);

// Monta a tabela mostrando o total de cidades em cada continente
while ($row = $res2->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['continente']) . "</td>
            <td>" . $row['total'] . "</td>
          </tr>";
}
?>
    </tbody>
  </table>

  <p><a class="btn secondary" href="index.php">Voltar</a></p>
</section>

<?php require 'footer.php'; ?>
