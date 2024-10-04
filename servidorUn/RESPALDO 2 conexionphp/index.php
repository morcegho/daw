<!DOCTYPE html>
<html>

<head>
  <title>

    aquí ocorrerá a maxia

  </title>
</head>
<!-- // $sql = INSERT INTO usuarios (dni, nome, primeiro_apelido, segundo_apelido, enderezo, telefono, nacemento) VALUES ('Pepiño', 'prz', 'rgz', 'dfsdfs sdfs fs dfs', '986766666', '05/06/1990'); -->

<body>
  <br>
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
    <?php
    // $server='localhost' ; $user='root' ; $pass='Contrasinal111' ; $db='nova_base' ;
    $conectar = mysqli_connect('localhost', 'root', 'Contrasinal111', 'nova_base') or die("Erro conectando");
function insertar($conectar) {
  $dni = $_POST["dni"];
  $nome = $_POST["nome"];
  $primeiro_apelido = $_POST["primeiro_apelido"];
  $segundo_apelido = $_POST["segundo_apelido"];
  $enderezo = $_POST["enderezo"];
  $telefono = $_POST["telefono"];
  $nacemento = $_POST["nacemento"];
  $consulta = "INSERT INTO usuarios (dni, nome, primeiro_apelido, segundo_apelido, enderezo, telefono, nacemento)
  VALUES (`$dni`,`$nome`,`$primeiro_apelido`,`$segundo_apelido`,`$enderezo`,`$telefono`,`$nacemento`)";
  mysqli_query($conectar, $consulta);
  mysqli_close($conectar);
}


    $sql = "SELECT * from nova_base.usuarios";
    // $sqlPorNome ="SELECT * FROM usuarios WHERE nome = $nomeintroducido";
    // $sqlApelidoUn = "SELECT * FROM nova_base.usuarios WHERE dni = $dniIntroducido";
    // $sqlPosterior = "SELECT * FROM nova_base.usuarios WHERE nome DISTINCT $nomeintroducido";
    // $borrarUsuario = "DELETE FROM nova_base.usuarios WHERE dni = $dniIntroducido or .................";

$madar = insertar($conectar);

    // $nomeIntroducido;
    // $dniIntroducido;

    $result = mysqli_query($conectar, $sql);

//  insertar($conectar);


    while ($mostrar = mysqli_fetch_array($result)) {
      ?>
      <tr>
        <td><?php echo $mostrar['dni'] ?></td>
        <td><?php echo $mostrar['nome'] ?></td>
        <td><?php echo $mostrar['primeiro_apelido'] ?></td>
        <td><?php echo $mostrar['segundo_apelido'] ?></td>
        <td><?php echo $mostrar['enderezo'] ?></td>
        <td><?php echo $mostrar['telefono'] ?></td>
        <td><?php echo $mostrar['nacemento'] ?></td>
      </tr>
      <?php
    }
    ?>
  </table>
  <h3>Menú principal</h3>

  <ul>
    <li><a href="#">Novo alumno</a></li>
    <li><a href="#">Listar alumnos</a></li>
    <li><a href="#">Editar alumnos</a></li>
    <li><a href="#">Eliminar alumnos</a></li>
    <li><a href="#">xxxxx</a></li>
  </ul>
  <h3>submenús que implementar</h3>
  <h4>submenú de Novo alumno</h4>
  <!-- <ul>
        <li><a href="#">Dni alumno</a></li>
        <li><a href="#">Nome</a></li>
        <li><a href="#">1º apelido</a></li>
        <li><a href="#">2º apelido</a></li>
        <li><a href="#">Enderezo</a></li>
        <li><a href="#">Teléfono</a></li>
        <li><a href="#">Data de nacemento</a></li>
    </ul> -->
  <form action="/my-handling-form-page" method="post">
    <!-- <ul> -->
      <!-- <li> -->
        <!-- <label for="dni">DNI:</label> -->
        <input type="text" id="dni" name="user_dni" placeholder="DNI" />
      <!-- </li>
      <li>
        <label for="nome">Nome:</label> -->
        <input type="text" id="nome" name="user_name" placeholder="Nome"/>
      <!-- </li>
      <li> -->
        <!-- <label for="primeiro_apelido">1º apelido:</label> -->
        <input type="text" id="primeiro_apelido" name="user_primeiro_apelido" placeholder="1º apelido"/>
      <!-- </li>
      <li> -->
        <!-- <label for="segundo_apelido">2º apelido:</label> -->
        <input type="text" id="segundo_apelido" name="user_segundo_apelido" placeholder="2º apelido"/>
      <!-- </li>
      <li> -->
        <!-- <label for="enderezo">Enderezo:</label> -->
        <input type="text" id="enderezo" name="user_enderezo" placeholder="Enderezo"/>
      <!-- </li>
      <li> -->
        <!-- <label for="telefono">Teléfono:</label> -->
        <input type="text" id="telefono" name="user_telefono" placeholder="Teléfono"/>
      <!-- </li>
      <li> -->
        <!-- <label for="nacemento">Data de nacemento:</label> -->
        <input type="text" id="nacemento" name="user_nacemento" placeholder="Data de nacemento"/>
      <!-- </li>
      <li> -->
        <!-- <label for="msg">Mensaje:</label> -->
        <!-- <textarea id="msg" name="user_message"></textarea> -->
      <!-- </li>
    </ul> -->
  </form>
  <button type="submit" name="enviar" $mandar >Enviar</button>

  <h4>submenú de listar alumno</h4>
  <ul>
    <li>
      <label for="buscarnome">Buscar por nome:</label>
      <input type="text" id="dgfdgd" name="user_fdgfdgdg" />
    </li>
    <li>
      <label for="dfg">Buscar por data de nacemento:</label>
      <input type="text" id="dgfdgd" name="user_fdgfdgdg" />
    </li>
    <li>
      <label for="dfg">Buscar por dni:</label>
      <input type="text" id="dgfdgd" name="user_fdgfdgdg" />
    </li>
    <li><a href="#">Buscar por nome</a></li>

    <li><a href="#">Todos</a></li>
    <li><a href="#">Atrás</a></li>
  </ul>
  <h4>submenú de Editar alumno</h4>
  <ul>
    <li><a href="#">Buscar por dni</a></li>
    <li><a href="#">Editar....</a></li>
    <li><a href="#">Atrás</a></li>
  </ul>
  <h4>submenú de eliminar alumno</h4>
  <ul>
    <li><a href="#">Buscar por nome</a></li>
    <li><a href="#">Buscar por dni</a></li>
    <li><a href="#">borrar....</a></li>
    <li><a href="#">Atrás</a></li>
  </ul>



  <h1>TAREFAS: VALIDAR DNI AO CREAR E AO EDITAR</h1>
  <h1>TAREFAS: EDITAR BD</h1>

</body>

</html>