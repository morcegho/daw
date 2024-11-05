<?php
// Conexión á base de datos: 
// mysqli_connect é unha función que tenta conectar cun servidor MySQL.
// Requirimos 4 parámetros: o servidor, o usuario de MySQL, o contrasinal, e o nome da base de datos.
$conectar = mysqli_connect('localhost', 'root', 'Contrasinal111', 'nova_base') or die("Erro conectando á base de datos.");

// Función que se encarga de insertar un novo usuario na base de datos.
function insertar($conectar) {
    // Primeiro, recollemos os datos do formulario que o usuario enviou usando POST.
    // Estes datos inclúen o dni, nome, apelidos, enderezo, etc.
    $dni = $_POST["dni"];
    $nome = $_POST["nome"];
    $primeiro_apelido = $_POST["primeiro_apelido"];
    $segundo_apelido = $_POST["segundo_apelido"];
    $enderezo = $_POST["enderezo"];
    $telefono = $_POST["telefono"];
    $nacemento = $_POST["nacemento"];

    // Validamos que a data teña o formato correcto.
    if (!validarData($nacemento)) {
        echo "Formato de data incorrecto.";
        return false;
    }

    // Usamos unha consulta preparada (prepared statement) para evitar inxección de SQL.
    // Con bind_param, substituímos as incógnitas (?) polos valores que introduciu o usuario.
    $stmt = $conectar->prepare("INSERT INTO usuarios (dni, nome, primeiro_apelido, segundo_apelido, enderezo, telefono, nacemento) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $dni, $nome, $primeiro_apelido, $segundo_apelido, $enderezo, $telefono, $nacemento);

    // Executamos a consulta e comprobamos se foi exitosa.
    if ($stmt->execute()) {
        echo "Usuario insertado correctamente.";
    } else {
        echo "Erro ao insertar usuario: " . $stmt->error;
    }

    // Pechamos o prepared statement.
    $stmt->close();
}

// Función para validar o formato da data. 
// A data debe ter o formato 'ano-mes-día' (Y-m-d).
function validarData($fecha) {
    $formato = 'Y-m-d';
    $d = DateTime::createFromFormat($formato, $fecha);
    return $d && $d->format($formato) === $fecha;
}

// Función que filtra os usuarios segundo varios criterios (nome, ano de nacemento, letra do DNI).
function filtrarCombinado($conectar, $nome, $ano, $operador, $letra) {
    $condiciones = [];
    
    // Se o usuario introduciu un nome, engadimos esa condición á consulta SQL.
    if (!empty($nome)) {
        $nome = mysqli_real_escape_string($conectar, $nome); // Limpeza de datos para evitar inxección SQL
        $condiciones[] = "nome LIKE '%$nome%'"; // Buscamos calquera nome que conteña a cadea introducida
    }

    // Se o usuario introduciu un ano, engadimos unha condición para comparar o ano de nacemento.
    if (!empty($ano)) {
        $ano = mysqli_real_escape_string($conectar, $ano);
        $condiciones[] = "YEAR(nacemento) $operador '$ano'";
    }

    // Se o usuario introduciu unha letra para o DNI, engadimos esa condición.
    if (!empty($letra)) {
        $letra = mysqli_real_escape_string($conectar, $letra);
        $condiciones[] = "dni LIKE '%$letra'";
    }

    // Creamos a consulta base. Se hai condicións, engadímolas á consulta.
    $consulta = "SELECT * FROM usuarios";
    if (count($condiciones) > 0) {
        $consulta .= " WHERE " . implode(' AND ', $condiciones);
    }

    // Executamos a consulta SQL e devolvemos os resultados.
    return mysqli_query($conectar, $consulta);
}

// Comprobamos se o usuario enviou o formulario para engadir un novo usuario.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar'])) {
    if (insertar($conectar)) {
        // Se o usuario foi engadido correctamente, rediriximos para evitar que se reenvíe o formulario ao actualizar a páxina.
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Comprobamos se o usuario utilizou algún dos filtros.
$result = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recollemos os valores dos filtros (nome, ano de nacemento, operador 'anterior' ou 'posterior', letra do DNI).
    $nome = $_POST['filtrar_nome'] ?? '';
    $ano = $_POST['filtrar_ano'] ?? '';
    $operador = $_POST['tipo_filtro'] === 'anterior' ? '<' : '>';
    $letra = $_POST['filtrar_dni_letra'] ?? '';

    // Aplicamos o filtro combinado en función dos campos completados.
    $result = filtrarCombinado($conectar, $nome, $ano, $operador, $letra);
} else {
    // Se non se aplicou ningún filtro, obtemos todos os usuarios.
    $sql = "SELECT * FROM usuarios";
    $result = mysqli_query($conectar, $sql) or die("Erro na consulta: " . mysqli_error($conectar));
}

// Finalmente, pechamos a conexión á base de datos.
mysqli_close($conectar);
?>
