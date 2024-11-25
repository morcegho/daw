<!DOCTYPE html>
<html lang="gl">
<head>
  <!-- Metadatos básicos da páxina HTML -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aquí ocorrerá a maxia</title>
  <!-- Ligazón a un arquivo CSS para a estilización da páxina -->
  <link rel="stylesheet" href="estilos.css">
</head>
<body>

  <!-- Táboa para mostrar os datos dos usuarios almacenados na base de datos -->
  <br>
  <table border="1">
    <tr>
      <!-- Cabeceira da táboa que define o nome das columnas -->
      <td>Ficha</td>
      <td>Nome</td>
      <td>Código usuario</td>
      <td>Saldo</td>
    </tr>

    <!-- PHP: incluímos un arquivo que contén a conexión á base de datos -->
    <?php include 'conexionFormulario.php'; ?>

    <!-- PHP: mentres haxa resultados na consulta, mostraremos cada rexistro como unha nova fila na táboa -->
    <?php while ($mostrar = mysqli_fetch_array($result)) { ?>
      <tr>
        <!-- PHP: imprimimos os valores de cada campo dentro das correspondentes celas da táboa -->
        <td><?php echo $mostrar['idFicha']; ?></td>
        <td><?php echo $mostrar['nome']; ?></td>
        <td><?php echo $mostrar['idUsuario']; ?></td>
        <td><?php echo $mostrar['saldo']; ?></td>
    
      </tr>
    <?php } ?>
  </table>


</body>
</html>
