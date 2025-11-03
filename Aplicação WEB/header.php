<?php
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Gerenciador de Países e Cidades</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1>Gerenciador de Países e Cidades</h1>
      <nav>
        <a href="index.php">Início</a>
        <a href="countries.php">Países</a>
        <a href="cities.php">Cidades</a>
        <a href="stats.php">Estatísticas</a>
      </nav>
    </div>
  </header>
  <main class="container">
    <?php
    if (!empty($_GET['msg'])) {
        $msg = htmlspecialchars($_GET['msg']);
        echo "<div class='msg'>{$msg}</div>";
    }
    ?>
