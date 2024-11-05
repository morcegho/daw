<?php
require 'vendor/autoload.php'; // Autoload para MongoDB sen configurar
//non está configurado para funcionar realmente
// preciso instalar composer

// Conéctase a MongoDB Atlas
$client = new MongoDB\Client("mongodb+srv://mvilceb:noliu111@cluster0.iqwdukh.mongodb.net/?retryWrites=true&w=majority");

// Selecciona a base de datos e a colección --- NON EXISTE INDA
$database = $client->nova_base;
$coleccion = $database->usuarios;

// Función para insertar usuario
function insertar($coleccion) {
    // Recolle datos do formulario
    $dni = $_POST["dni"];
    $nome = $_POST["nome"];
    $primeiro_apelido = $_POST["primeiro_apelido"];
    $segundo_apelido = $_POST["segundo_apelido"];
    $enderezo = $_POST["enderezo"];
    $telefono = $_POST["telefono"];
    $nacemento = $_POST["nacemento"];

    // Validar formato da data (se necesario)
    if (!validarData($nacemento)) {
        echo "Formato de data incorrecto.";
        return false;
    }

    // Meter o usuario en MongoDB
    $coleccion->insertOne([
        'dni' => $dni,
        'nome' => $nome,
        'primeiro_apelido' => $primeiro_apelido,
        'segundo_apelido' => $segundo_apelido,
        'enderezo' => $enderezo,
        'telefono' => $telefono,
        'nacemento' => $nacemento
    ]);

    echo "Usuario insertado correctamente.";
}

// Función para validar datas
function validarData($data) {
    $formato = 'Y-m-d';
    $d = DateTime::createFromFormat($formato, $data);
    return $d && $d->format($formato) === $data;
}

// Función para filtrar usuarios
function filtrarCombinado($coleccion, $nome, $ano, $operador, $letra) {
    $filtros = [];
    // comproba se foron marcadod os parámetros
    if (!empty($nome)) {
        $filtros['nome'] = ['$regex' => $nome, '$options' => 'i'];
    }
    if (!empty($ano)) {
        $filtros['nacemento'] = [$operador => new MongoDB\BSON\UTCDateTime(strtotime($ano . "-01-01") * 1000)];
    }
    if (!empty($letra)) {
        $filtros['dni'] = ['$regex' => $letra . '$'];
    }

    return $coleccion->find($filtros);
}

// Traballar a inserción de datos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar'])) {
    insertar($coleccion);
}

// Manexar filtros e consultas
$result = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['filtrar_nome'] ?? '';
    $ano = $_POST['filtrar_ano'] ?? '';
    $operador = $_POST['tipo_filtro'] === 'anterior' ? '$lt' : '$gt';
    $letra = $_POST['filtrar_dni_letra'] ?? '';

    $result = filtrarCombinado($coleccion, $nome, $ano, $operador, $letra);
} else {
    $result = $coleccion->find();
}
?>

<!DOCTYPE html>
<html lang="gl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MongoDB Atlas</title>
</head>
<body>
<h3>Engadir unha nova entrada ao arquivo:</h3>
<form method="POST">
  <input type="text" id="dni" name="dni" placeholder="DNI" required />
  <input type="text" id="nome" name="nome" placeholder="Nome" required />
  <input type="text" id="primeiro_apelido" name="primeiro_apelido" placeholder="1º apelido" required />
  <input type="text" id="segundo_apelido" name="segundo_apelido" placeholder="2º apelido" required />
  <input type="text" id="enderezo" name="enderezo" placeholder="Enderezo" required />
  <input type="text" id="telefono" name="telefono" placeholder="Teléfono" required />
  <input type="date" id="nacemento" name="nacemento" required />
  <button type="submit" name="enviar">Enviar</button>
</form>

<h3>Usuarios:</h3>
<table border="1">
  <tr>
    <td>DNI</td>
    <td>Nome</td>
    <td>Primeiro Apelido</td>
    <td>Segundo Apelido</td>
    <td>Enderezo</td>
    <td>Teléfono</td>
    <td>Data de Nacemento</td>
  </tr>

  <?php foreach ($result as $usuario) { ?>
  <tr>
    <td><?php echo $usuario['dni']; ?></td>
    <td><?php echo $usuario['nome']; ?></td>
    <td><?php echo $usuario['primeiro_apelido']; ?></td>
    <td><?php echo $usuario['segundo_apelido']; ?></td>
    <td><?php echo $usuario['enderezo']; ?></td>
    <td><?php echo $usuario['telefono']; ?></td>
    <td><?php echo $usuario['nacemento']->toDateTime()->format('Y-m-d'); ?></td>
  </tr>
  <?php } ?>
</table>

</body>
</html>
