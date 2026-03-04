<?php

    $paginaAtual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($paginaAtual < 1) $paginaAtual = 1;

    $pesquisa = $_GET['search'] ?? '';

    $url = "https://api.disneyapi.dev/character?pageSize=50&page=" . $paginaAtual;

    if(!empty($pesquisa)){
        $url .= "&name=" . urlencode($pesquisa);
    }

    $context = stream_context_create(['http' => ['timeout' => 10]]);
    $response = @file_get_contents($url, false, $context);
    $data = $response ? json_decode($response, true) : null;

    $personagens = $data['data'] ?? [];
    $totalPaginas = $data['info']['totalPages'] ?? 1;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Disney</title>
		<link href="css/style.css?v=<?php echo rand();?>" type="text/css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
</head>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("pesquisa");
    const cards = document.querySelectorAll(".card");

    input.addEventListener("input", function () {
        const termo = input.value.toLowerCase();

        cards.forEach(card => {
            const textoCard = card.innerText.toLowerCase();

            if (textoCard.includes(termo)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
});
</script>

<body>

<div class="topo">
    <h1>Disney</h1>
    <img src="images/Disney-Logo.png">
    <form method="GET">
        <input type="text" name="search" placeholder="Pesquisar" value="<?php echo $pesquisa ?? ''; ?>">
    </form>

</div>

<div class="container">

    <?php foreach ($personagens as $personagem) { ?>

    <div class="card">
        <div class="titulo">
            <h2><?php echo "" . $personagem['name'] . "<br>"; ?></h2>
        </div>

        <h3>
            <?php echo "Filmes: " . implode(", ", $personagem['films']) . "<br><br>"; ?>
        </h3>

        <div class="img">
            <img 
            width="200"
            height="200"
            src="<?php echo ( !empty($personagem['imageUrl']) ) ? $personagem['imageUrl'] : 'https://static.wikia.nocookie.net/disney/images/d/dd/X240-pex.jpg'; ?>"
            alt="<?php echo $personagem['name']; ?>">
            <br><br>
        </div><!-- img -->
    </div><!-- card -->


<?php } ?>

    <ul class="pagination">
    
        <?php if($paginaAtual > 1): ?>
            <li><a href="?page=<?php echo $paginaAtual - 1; ?>">&laquo;</a></li>
        <?php endif; ?>

        <?php 
        $inicio = max(1, $paginaAtual - 2);
        $fim = min($totalPaginas, $paginaAtual + 2);
        for ($i = $inicio; $i <= $fim; $i++): 
        ?>
            <li class="<?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if($paginaAtual < $totalPaginas): ?>
            <li><a href="?page=<?php echo $paginaAtual + 1; ?>">&raquo;</a></li>
        <?php endif; ?>
    </ul>

</div><!-- container -->
<div class="footer"><h2>Direitos Reservados</h2></div>
</body>

</html>