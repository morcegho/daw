<?php
// Establecer a conexión á base de datos
$conectar = mysqli_connect('localhost', 'root', 'Contrasinal111', 'nova_base') or die("Erro conectando á base de datos.");

// Función para insertar datos na base de datos
function insertar($conectar) {
    $dni = mysqli_real_escape_string($conectar, $_POST["dni"]);
    $nome = mysqli_real_escape_string($conectar, $_POST["nome"]);
    $primeiro_apelido = mysqli_real_escape_string($conectar, $_POST["primeiro_apelido"]);
    $segundo_apelido = mysqli_real_escape_string($conectar, $_POST["segundo_apelido"]);
    $enderezo = mysqli_real_escape_string($conectar, $_POST["enderezo"]);
    $telefono = mysqli_real_escape_string($conectar, $_POST["telefono"]);
    $nacemento = mysqli_real_escape_string($conectar, $_POST["nacemento"]);

    // Comprobar se o DNI xa existe na base de datos
    $comprobar_query = "SELECT * FROM usuarios WHERE dni = '$dni'";
    $comprobar_result = mysqli_query($conectar, $comprobar_query);

    if (mysqli_num_rows($comprobar_result) > 0) {
        echo "Xa existe este DNI na base de datos.";
        return false; // O DNI xa existe
    }

    // Se o DNI non existe, inserta nabase de datos
    $consulta = "INSERT INTO usuarios (dni, nome, primeiro_apelido, segundo_apelido, enderezo, telefono, nacemento)
                 VALUES ('$dni', '$nome', '$primeiro_apelido', '$segundo_apelido', '$enderezo', '$telefono', '$nacemento')";
    mysqli_query($conectar, $consulta) or die("Erro insertando: " . mysqli_error($conectar));
    return true;
}

// Funcións para os filtros
function filtrarPorNome($conectar, $nome) {
    $nome = mysqli_real_escape_string($conectar, $nome);
    $consulta = "SELECT * FROM usuarios WHERE nome LIKE '%$nome%'";
    return mysqli_query($conectar, $consulta);
}

function filtrarPorAno($conectar, $ano, $operador) {
    $ano = mysqli_real_escape_string($conectar, $ano);
    $consulta = "SELECT * FROM usuarios WHERE YEAR(nacemento) $operador '$ano'";
    return mysqli_query($conectar, $consulta);
}

function filtrarPorLetraFinalDNI($conectar, $letra) {
    $letra = mysqli_real_escape_string($conectar, $letra);
    $consulta = "SELECT * FROM usuarios WHERE dni LIKE '%$letra'";
    return mysqli_query($conectar, $consulta);
}

// Manexar o envío do formulario de inserción
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar'])) {
    if (insertar($conectar)) {
        // Redirixir para evitar o reenvío do formulario
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Manexar os filtros
$result = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filtrar_nome'])) {
    $result = filtrarPorNome($conectar, $_POST['filtrar_nome']);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filtrar_ano'])) {
    $operador = $_POST['tipo_filtro'] === 'anterior' ? '<' : '>';
    $result = filtrarPorAno($conectar, $_POST['filtrar_ano'], $operador);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filtrar_dni_letra'])) {
    $result = filtrarPorLetraFinalDNI($conectar, $_POST['filtrar_dni_letra']);
} else {
    // Consulta por defecto para mostrar todos os usuarios
    $sql = "SELECT * FROM usuarios";
    $result = mysqli_query($conectar, $sql) or die("Erro na consulta: " . mysqli_error($conectar));
}

// Pechar a conexión despois de executar as operacións
mysqli_close($conectar);
?>
