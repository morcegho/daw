<!DOCTYPE html>
<html lang="gl">
<head>
  <!-- Metadatos básicos da páxina HTML -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aquí ocorrerá a maxia</title>
  <!-- Ligazón a CSS -->
  <link rel="stylesheet" href="estilos.css">
</head>
<body>

<h3>Engadir unha nova entrada ao arquivo:</h3>

  <!-- Formulario para engadir novos usuarios á base de datos -->
  <form method="POST">
    <!-- O campo 'dni'  obrigatorio (required) -->
    <input type="text" id="dni" name="dni" placeholder="DNI" required />
    <!-- Campo 'nome' -->
    <input type="text" id="nome" name="nome" placeholder="Nome" required />
    <!-- Campo para o primeiro apelido -->
    <input type="text" id="primeiro_apelido" name="primeiro_apelido" placeholder="1º apelido" required />
    <!-- Campo para o segundo apelido -->
    <input type="text" id="segundo_apelido" name="segundo_apelido" placeholder="2º apelido" required />
    <!-- Campo para o enderezo -->
    <input type="text" id="enderezo" name="enderezo" placeholder="Enderezo" required />
    <!-- Campo para o teléfono -->
    <input type="text" id="telefono" name="telefono" placeholder="Teléfono" required />
    <!-- Campo para a data de nacemento (formato de calendario HTML5) -->
    <input type="date" id="nacemento" name="nacemento" placeholder="Data de nacemento" required />
    <!-- Botón para enviar o formulario -->
    <button type="submit" name="enviar">Enviar</button>
  </form>

  <!-- mostrar os datos dos usuarios almacenados na base de datos -->
  <br>
  <h3>Usuarios:</h3>

  <table border="1">
    <tr>
      <!-- Cabeceira da táboa que define o nome das columnas -->
      <td>DNI</td>
      <td>Nome</td>
      <td>Primeiro Apelido</td>
      <td>Segundo Apelido</td>
      <td>Enderezo</td>
      <td>Teléfono</td>
      <td>Data de Nacemento</td>
    </tr>

    <!-- PHP: incluímos un arquivo que contén a conexión á base de datos -->
    <?php include 'conexion.php'; ?>

    <!-- PHP: mentres haxa resultados na consulta, mostras cada rexistro como unha nova fila -->
    <?php while ($mostrar = mysqli_fetch_array($result)) { ?>
      <tr>
        <!-- PHP: imprime os valores de cada campo dentro das correspondentes celas da táboa -->
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
  <!-- Formulario para buscar usuarios por nome -->
  <!-- <h3>Filtrar por nome:</h3>
  <form method="POST">
    <input type="text" name="filtrar_nome" placeholder="Nome">
    <button type="submit">Buscar</button>
  </form> -->

  <!-- Formulario para filtrar usuarios polo ano de nacemento -->
  <!-- <h3>Filtrar por ano de nacemento:</h3>
  <form method="POST">
    <select name="tipo_filtro"> -->
      <!-- Opción para buscar usuarios nacidos antes dun ano específico -->
      <!-- <option value="anterior">Anterior a</option> -->
      <!-- Opción para buscar usuarios nacidos despois dun ano específico -->
      <!-- <option value="posterior">Posterior a</option>
    </select>
    <input type="number" name="filtrar_ano" placeholder="Ano">
    <button type="submit">Filtrar</button>
  </form>  -->

  <!-- Formulario para filtrar usuarios por letra final do DNI -->
  <!-- <h3>Filtrar por letra final do DNI:</h3>
  <form method="POST">
    <input type="text" name="filtrar_dni_letra" maxlength="1" placeholder="Letra final">
    <button type="submit">Filtrar</button>
  </form> -->

  <!-- Formulario para filtrar usuarios por varios campos ao mesmo tempo -->
  <h3>Filtrar por varios campos:</h3>
  <form method="POST">
    <input type="text" name="filtrar_nome" placeholder="Nome">
    <select name="tipo_filtro">
      <option value="anterior">Anterior a</option>
      <option value="posterior">Posterior a</option>
    </select>
    <input type="number" name="filtrar_ano" placeholder="Ano de nacemento">

    <input type="text" name="filtrar_dni_letra" maxlength="1" placeholder="Letra final do DNI">
    <button type="submit">Filtrar</button>
  </form>

</body>
</html>
