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

  <!-- Formulario para engadir novos usuarios á base de datos -->
  <form method="POST">
    <!-- O campo 'dni' onde o usuario introduce o DNI, obrigatorio (required) -->
    <input type="text" id="idFicha" name="idFicha" placeholder="Nº da ficha" required />
    <!-- Campo 'nome', onde o usuario introduce o nome -->
    <input type="text" id="nome" name="nome" placeholder="Nome" required />
    <!-- Campo para o primeiro apelido -->
    <input type="text" id="idUsuario" name="idUsuario" placeholder="Código d eusuario" required />
    <!-- Campo para o segundo apelido -->
    <input type="text" id="saldo" name="saldo" placeholder="Cifra" required />
    <!-- Botón para enviar o formulario -->
    <button type="submit" name="enviar">Enviar</button>
  </form>

  <!-- Formulario para buscar usuarios por nome -->
  <h3>Filtrar por nome:</h3>
  <form method="POST">
    <input type="text" name="filtrar_nome" placeholder="Nome">
    <button type="submit">Buscar</button>
  </form>

  <!-- Formulario para filtrar usuarios polo ano de nacemento -->
  <h3>Filtrar por ano de nacemento:</h3>
  <form method="POST">
    <select name="tipo_filtro">
      <!-- Opción para buscar usuarios nacidos antes dun ano específico -->
      <option value="anterior">Anterior a</option>
      <!-- Opción para buscar usuarios nacidos despois dun ano específico -->
      <option value="posterior">Posterior a</option>
    </select>
    <input type="number" name="filtrar_ano" placeholder="Ano">
    <button type="submit">Filtrar</button>
  </form>

  <!-- Formulario para filtrar usuarios por letra final do DNI -->
  <h3>Filtrar por letra final do DNI:</h3>
  <form method="POST">
    <input type="text" name="filtrar_dni_letra" maxlength="1" placeholder="Letra final">
    <button type="submit">Filtrar</button>
  </form>

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
