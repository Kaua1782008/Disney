<!DOCTYPE html>
<html lang="en">
<head>
    <title>Disney</title>
		<link href="css/style.css?v=<?php echo rand();?>" type="text/css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8" />
</head>
<body>

<div class="topo">
    <h1>Disney</h1>
    <img src="images/Disney-Logo.png">
</div>

<div class="container">
<?php
    $url = "https://api.disneyapi.dev/character?pageSize=17";


    $context = stream_context_create(['http' => ['timeout' => 10]]);
    $response = @file_get_contents($url, false, $context);

    $data = $response ? json_decode($response, true) : null;

    $personagens = $data['data'] ?? [];
?>

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
            src="<?php echo $personagem['imageUrl']; ?>"
            alt="<?php echo $personagem['name']; ?>">
            <br><br>
        </div><!-- img -->
    </div><!-- card -->


<?php } ?>

</div><!-- container -->
<div class="footer"><h2>Direitos Reservados</h2></div>
</body>

</html>