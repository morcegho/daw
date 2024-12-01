<?php
// Importar o arquivo que contén as funcións CRUD (crear, ler, actualizar, eliminar)
require 'aforcadoCrud.php';  

// Iniciar sesión para gardar datos entre diferentes peticions
session_start();

// Array de palabras posibles
$palabras = ['programacion', 'php', 'aforcado', 'mongodb', 'servidor'];

// Se non hai unha palabra xa posta, seleccionar unha aleatoriamente
//isset=determina se unha variable foi declarada ou non e non é null
if (!isset($_SESSION['palabra'])) {
    $_SESSION['palabra'] = $palabras[array_rand($palabras)];
}

// Obter a palabra do aforcado da sesión
$palabra = $_SESSION['palabra'];

// Obter a lonxitude da palabra seleccionada
$lonxitudePalabra = strlen($palabra);

// Comproba se hai un usuario logueado e asigna o nome
$usuarioLogueado = isset($_SESSION['nome']);
$nomeUsuario = $usuarioLogueado ? $_SESSION['nome'] : ''; // Se está logueado, asignar nome de usuario

// Inicia o xogo, valores iniciais xogo
function iniciarXogo($lonxitudePalabra) {
    $_SESSION['letrasAdivinadas'] = str_repeat('_', $lonxitudePalabra); // Inicializar letras adiviñadas (en branco ó principio, todo _)
    $_SESSION['intentosFallidos'] = 0;
    $_SESSION['letrasIntentadas'] = []; // array de letras usadas
    $_SESSION['puntos'] = 100;  // Puntos iniciais (100 puntos, van restando 5 en cada fallo)
}

// Función que actualiza o estado do xogo despois de adiviñar ou fallar unha letra
function actualizarEstado($letra, &$letrasAdivinadas, $palabra, &$intentosFallidos, &$letrasIntentadas, &$puntos) {
    if (in_array($letra, $letrasIntentadas)) return false; // Se a letra xa foi intentada, non facer nada

    $letrasIntentadas[] = $letra; // Engadir letra ao array de letras intentadas
    if (strpos($palabra, $letra) !== false) { // Se a letra está na palabra
        //strpos= string position, busca a posición da letra na palabra
        for ($i = 0; $i < strlen($palabra); $i++) { // Recorrer cada letra da palabra
            //strlen = string length, lonxitude da palabra
            if ($palabra[$i] == $letra) { // Se a letra coincide co carácter da palabra
                $letrasAdivinadas[$i] = $letra; // Substituír o _ por esa letra na palabra adiviñada
            }
        }
        return true;  // Retornar verdadeiro se a letra foi adiviñada correctamente
    } else { 
        $intentosFallidos++;  // Incrementar os intentos fallidos
        $puntos -= 5;  // Restar 5 puntos por cada fallo
        return false;  // Retornar falso se a letra non estaba na palabra
    }
}

// Procesar o login ou rexistro dun usuario
if (isset($_POST['login']) || isset($_POST['rexistrar'])) {
    $nome = $_POST['nome']; // Nome do usuario
    $password = $_POST['password']; // Contrasinal

    // Se é login, comprobar se o usuario e contrasinal son válidos
    if (isset($_POST['login']) && validarUsuario($nome, $password)) {
        $_SESSION['nome'] = $nome; // Gardar o nome de usuario na sesión
    } 
    // Se é rexistro, crear un novo usuario
    elseif (isset($_POST['rexistrar'])) {
        crearUsuario($nome, $password); // Crear usuario co nome e contrasinal
        echo "Usuario rexistrado. Podes iniciar sesión agora.<br>"; // Mensaxe de rexistro exitoso
    }
}

// Se se preme o botón "xogar", iniciar o xogo
if (isset($_POST['xogar'])) iniciarXogo($lonxitudePalabra);

// Procesar a letra adiviñada por un usuario
if (isset($_POST['letra']) && $usuarioLogueado) {
    $letra = strtolower($_POST['letra']);  // Converter a letra a minúscula
    $letrasAdivinadas = $_SESSION['letrasAdivinadas'];  // Obter o estado actual das letras adiviñadas
    $intentosFallidos = $_SESSION['intentosFallidos'];  // Intentos fallidos
    $letrasIntentadas = $_SESSION['letrasIntentadas'];  // Letras xa intentadas
    $puntos = $_SESSION['puntos'];  // Puntos actuais

    // Actualizar o estado do xogo coas novas letras adiviñadas
    if (actualizarEstado($letra, $letrasAdivinadas, $palabra, $intentosFallidos, $letrasIntentadas, $puntos)) {
        $_SESSION['letrasAdivinadas'] = $letrasAdivinadas;
        $_SESSION['intentosFallidos'] = $intentosFallidos;
        $_SESSION['letrasIntentadas'] = $letrasIntentadas;
        $_SESSION['puntos'] = $puntos;
    }

    // Comprobar se o xogador gañou ou perdeu
    if ($letrasAdivinadas == $palabra) { // Se adiviñou toda a palabra
        echo "Parabéns! Adiviñaches a palabra: $palabra<br>";
        engadirPuntuacion($nomeUsuario, $puntos); // Engadir a puntuación final ao usuario
        session_destroy();  // Terminar a sesión
    } elseif ($intentosFallidos >= 6) {  // Se o xogador chegou aos 6 fallos
        echo "Perdiches! A palabra era: $palabra<br>";
        session_destroy();  // Terminar a sesión
    }
}

// Procesar a saída do xogo (logout)
if (isset($_POST['logout'])) {
    session_destroy();  // Terminar a sesión
    header("Location: aforcado.php"); // Redirixir á páxina principal
    exit;  // Terminar o script
}

// Función para ver o historial de puntuacións dun usuario
function verPuntuacions($usuario) {
    return lerUsuario($usuario);  // Ler o historial de puntuacións do usuario
}

?>

<!DOCTYPE html>
<html lang="gl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xogo do Aforcado</title>
</head>
<body>
    <?php if (!$usuarioLogueado): ?>
        <h2>Inicia sesión ou rexístrate</h2>
        <form method="POST">
            <label>Nome:</label><br>
            <input type="text" name="nome" required><br>
            <label>Contrasinal:</label><br>
            <input type="password" name="password" required><br>
            <button type="submit" name="login">Iniciar sesión</button>
            <button type="submit" name="rexistrar">Rexistrarse</button>
        </form>
    <?php else: ?>
        <h2>Benvido, <?php echo htmlspecialchars($nomeUsuario); ?></h2>

        <?php if (!isset($_SESSION['letrasAdivinadas'])): ?>
            <form method="POST">
                <button type="submit" name="xogar">Xogar partida</button>
            </form>

            <!-- Mostrar historial de puntuacións -->
            <form method="POST">
                <button type="submit" name="ver_puntuacions">Ver historial de puntuacións</button>
            </form>
            <?php
            if (isset($_POST['ver_puntuacions'])) {
                $puntuacions = verPuntuacions($nomeUsuario);
                // Mostrar puntuacións
                // echo "<h3>Historial de puntuacións:</h3>";
                // foreach ($puntuacions as $puntuacion) {
                //     echo "<p>Data: " . $puntuacion['data'] . " - Puntuación: " . $puntuacion['puntuacion'] . "</p>";
                // }
            }
            ?>
        <?php else: ?>
            <p>Adiviña a palabra: <?php echo $_SESSION['letrasAdivinadas']; ?></p>
            <p>Letras intentadas: <?php echo implode(', ', $_SESSION['letrasIntentadas']); ?></p>
            <p>Intentos restantes: <?php echo 6 - $_SESSION['intentosFallidos']; ?></p>
            <p>Puntos restantes: <?php echo $_SESSION['puntos']; ?></p>
            
            <form method="POST">
                <label>Introduce unha letra:</label><br>
                <input type="text" name="letra" maxlength="1" required><br>
                <button type="submit">Enviar</button>
            </form>

            <form method="POST">
                <button type="submit" name="logout">Saír</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
