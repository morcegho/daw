<!DOCTYPE html>
<html lang="gl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aquí ocorrerá a maxia</title>
  <link rel="stylesheet" href="estilos.css"> <!-- Asegúrate de que el archivo estilos.css esté en la misma carpeta -->
</head>
<body>
  <br>
  <!-- Tabela para mostrar os datos -->
  <table border="1">
    <tr>
      <td>dni</td>
      <td>nome</td>
      <td>primeiro apelido</td>
      <td>segundo apelido</td>
      <td>enderezo</td>
      <td>teléfono</td>
      <td>data de nacemento</td>
    </tr>

    <!-- Incluír o código de conexión e consulta a base de datos -->
    <?php include 'conexion.php'; ?>

    <!-- Mostrar os datos da base de datos -->
    <?php while ($mostrar = mysqli_fetch_array($result)) { ?>
      <tr>
        <td><?php echo $mostrar['dni']; ?></td>
        <td><?php echo $mostrar['nome']; ?></td>
        <td><?php echo $mostrar['primeiro_apelido']; ?></td>
        <td><?php echo $mostrar['segundo_apelido']; ?></td>
        <td><?php echo $mostrar['enderezo']; ?></td>
        <td><?php echo $mostrar['telefono']; ?></td>
        <td><?php echo $mostrar['nacemento']; ?></td>
      </tr>
    <?php } ?>
  </table>

  <!-- engadir novo usuario -->
  <form method="POST">
    <input type="text" id="dni" name="dni" placeholder="DNI" required />
    <input type="text" id="nome" name="nome" placeholder="Nome" required />
    <input type="text" id="primeiro_apelido" name="primeiro_apelido" placeholder="1º apelido" required />
    <input type="text" id="segundo_apelido" name="segundo_apelido" placeholder="2º apelido" required />
    <input type="text" id="enderezo" name="enderezo" placeholder="Enderezo" required />
    <input type="text" id="telefono" name="telefono" placeholder="Teléfono" required />
    <input type="date" id="nacemento" name="nacemento" placeholder="Data de nacemento" required />
    <button type="submit" name="enviar">Enviar</button>
  </form>

  <!-- filtrar por nome -->
  <h3>Filtrar por nome:</h3>
  <form method="POST">
    <input type="text" name="filtrar_nome" placeholder="Nome">
    <button type="submit">Buscar</button>
  </form>

  <!-- filtrar por ano de nacemento ///anterior e posterior a un ano-->
  <h3>Filtrar por ano de nacemento:</h3>
  <form method="POST">
    <input type="number" name="filtrar_ano" placeholder="Ano">
    <select name="tipo_filtro">
      <option value="anterior">Anterior a</option>
      <option value="posterior">Posterior a</option>
    </select>
    <button type="submit">Filtrar</button>
  </form>

  <!-- filtrar por letra do DNI -->
  <h3>Filtrar por letra final do DNI:</h3>
  <form method="POST">
    <input type="text" name="filtrar_dni_letra" maxlength="1" placeholder="Letra final">
    <button type="submit">Filtrar</button>
  </form>


  <h3>Filtrar por varios campos:</h3>
<form method="POST">
    <input type="text" name="filtrar_nome" placeholder="Nome">
    <input type="number" name="filtrar_ano" placeholder="Ano de nacemento">
    <select name="tipo_filtro">
      <option value="anterior">Anterior a</option>
      <option value="posterior">Posterior a</option>
    </select>
    <input type="text" name="filtrar_dni_letra" maxlength="1" placeholder="Letra final do DNI">
    <button type="submit">Filtrar</button>
</form>

</body>
</html>
