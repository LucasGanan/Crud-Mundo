<?php
require 'db.php';
require 'header.php';
?>
<section>
  <h2>Crud - Mundo</h2>
  <p>Gerenciamento de dados geográficos</p>

  <?php
  $resP = $mysqli->query("SELECT COUNT(*) as c FROM paises");
  $cntP = $resP->fetch_assoc()['c'] ?? 0;
  $resC = $mysqli->query("SELECT COUNT(*) as c FROM cidades");
  $cntC = $resC->fetch_assoc()['c'] ?? 0;
  ?>
  <div class="grid">
    <div class="card">
      <h3>Países</h3>
      <p class="big"><?php echo $cntP; ?></p>
      <a class="btn" href="countries.php">Gerenciar Países</a>
    </div>
    <div class="card">
      <h3>Cidades</h3>
      <p class="big"><?php echo $cntC; ?></p>
      <a class="btn" href="cities.php">Gerenciar Cidades</a>
    </div>
  </div>
</section>

<?php require 'footer.php'; ?>
