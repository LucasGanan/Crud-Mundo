<?php
// Importa o arquivo que cria a conexão com o banco de dados.
// Isso torna a variável $mysqli disponível para consultas SQL.
require 'db.php';

// Importa o cabeçalho do site, que contém a estrutura inicial e o menu.
require 'header.php';
?>

<section>
  <h2>Crud - Mundo</h2>
  <p>Gerenciamento de dados geográficos</p>

  <?php
  // Realiza uma consulta SQL para contar quantos registros existem na tabela "paises".
  $resP = $mysqli->query("SELECT COUNT(*) as c FROM paises");

  // Extrai o número de países retornado pela consulta. Caso não haja resultado, usa 0.
  $cntP = $resP->fetch_assoc()['c'] ?? 0;

  // Faz o mesmo para a tabela "cidades".
  $resC = $mysqli->query("SELECT COUNT(*) as c FROM cidades");
  $cntC = $resC->fetch_assoc()['c'] ?? 0;
  ?>
  
  <div class="grid">
    <div class="card glass">
      <h3>Países</h3>
      <p class="big"><?php echo $cntP; ?></p>
      <a class="btn glass" href="countries.php">Gerenciar Países</a>
    </div>
    <div class="card glass">
      <h3>Cidades</h3>
      <p class="big"><?php echo $cntC; ?></p>
      <a class="btn glass" href="cities.php">Gerenciar Cidades</a>
    </div>
  </div>
</section>

<?php
// Importa o rodapé do site.
require 'footer.php';
?>
