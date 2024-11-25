<!DOCTYPE html>
<html lang="gl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Páxina con PHP</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Benvido á miña páxina con PHP!</h1>
    <p>Data actual: <?php echo date('d/m/Y H:i:s'); ?></p>

    <form method="POST" action="procesar.php">
        <input type="text" name="nome" placeholder="Introduce o teu nome" required>
        <button type="submit">Enviar</button>
    </form>

</body>
</html>
