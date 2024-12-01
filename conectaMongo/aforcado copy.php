<?php
// Importar o CRUD
require 'aforcadoCrud.php';  // Supoñemos que o CRUD está gardado como "aforcadoCrud.php"

// Inicializar sesión
session_start();

// Xerar unha palabra aleatoria para o xogo
$palabras = ['programacion', 'php', 'aforcado', 'mongodb', 'servidor'];
if (!isset($_SESSION['palabra'])) {
    $_SESSION['palabra'] = $palabras[array_rand($palabras)];
}
$palabra = $_SESSION['palabra'];
$longitudPalabra = strlen($palabra);

// Comprobar se hai un usuario logueado
if (isset($_SESSION['nome'])) {
    $usuarioLogueado = true;
    $nomeUsuario = $_SESSION['nome'];
} else {
    $usuarioLogueado = false;
}

// Escollendo xogar, inicializa o xogo
if (isset($_POST['xogar'])) {
    $_SESSION['letrasAdivinadas'] = str_repeat('_', $longitudPalabra);
    $_SESSION['intentosFallidos'] = 0;
    $_SESSION['letrasIntentadas'] = [];
    $_SESSION['puntos'] = 100;  // Comeza con 100 puntos
}

// Función para actualizar o estado do xogo
function actualizarEstado($letra, &$letrasAdivinadas, $palabra, &$intentosFallidos, &$letrasIntentadas, &$puntos) {
    if (in_array($letra, $letrasIntentadas)) {
        echo "Xa intentaches esta letra.<br>";
        return false;
    }

    $letrasIntentadas[] = $letra;
    if (strpos($palabra, $letra) !== false) {
        for ($i = 0; $i < strlen($palabra); $i++) {
            if ($palabra[$i] == $letra) {
                $letrasAdivinadas[$i] = $letra;
            }
        }
        return true;
    } else {
        $intentosFallidos++;
        $puntos -= 5;  // Restar 5 puntos por cada fallo
        return false;
    }
}

// Procesamento do login ou rexistro
if (isset($_POST['login'])) {
    $nome = $_POST['nome'];
    $password = $_POST['password'];
    if (validarUsuario($nome, $password)) {
        $_SESSION['nome'] = $nome;
        $usuarioLogueado = true;
        $nomeUsuario = $nome;
    } else {
        echo "Login fallido. Intenta de novo.<br>";
    }
}

if (isset($_POST['registrar'])) {
    $nome = $_POST['nome'];
    $password = $_POST['password'];
    crearUsuario($nome, $password);
    echo "Usuario rexistrado. Podes iniciar sesión agora.<br>";
}

// Procesamento do xogo
if ($usuarioLogueado && isset($_POST['letra']) && isset($_SESSION['letrasAdivinadas'])) {
    $letra = strtolower($_POST['letra']);
    $letrasAdivinadas = $_SESSION['letrasAdivinadas'];
    $intentosFallidos = $_SESSION['intentosFallidos'];
    $letrasIntentadas = $_SESSION['letrasIntentadas'];
    $puntos = $_SESSION['puntos'];

    $resultado = actualizarEstado($letra, $letrasAdivinadas, $palabra, $intentosFallidos, $letrasIntentadas, $puntos);

    // Actualizar o estado do xogo na sesión
    $_SESSION['letrasAdivinadas'] = $letrasAdivinadas;
    $_SESSION['intentosFallidos'] = $intentosFallidos;
    $_SESSION['letrasIntentadas'] = $letrasIntentadas;
    $_SESSION['puntos'] = $puntos;

    // Comprobar se o usuario gañou ou perdeu
    if ($letrasAdivinadas == $palabra) {
        echo "Parabéns! Adiviñaches a palabra: $palabra<br>";
        engadirPuntuacion($nomeUsuario, $puntos); // Engadir puntuación
        unset($_SESSION['letrasAdivinadas']);
        unset($_SESSION['intentosFallidos']);
        unset($_SESSION['letrasIntentadas']);
    } elseif ($intentosFallidos >= 6) {
        echo "¡Perdiches! A palabra era: $palabra<br>";
        unset($_SESSION['letrasAdivinadas']);
        unset($_SESSION['intentosFallidos']);
        unset($_SESSION['letrasIntentadas']);
    }
}

// Saír da sesión
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: aforcado.php");
    exit;
}

// Función para ver o historial de puntuacións
function verPuntuacions($usuario) {
    // Aquí buscaríamos as puntuacións do usuario na base de datos
    // Deberías implementar a función que devolva as puntuacións do usuario
    // return obtenerPuntuacionsPorUsuario($usuario);
    return lerUsuario($usuario);

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
            <button type="submit" name="registrar">Rexistrarse</button>
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
