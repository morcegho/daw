<?php
// Habilitar reporte de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use MongoDB\Client;

// Conexión á base de datos
$uri = 'mongodb+srv://mvilceb:noliu111@cluster0.iqwdukh.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
$client = new Client($uri);
$databaseName = 'mdbPHP'; // Nome da base de datos
$collectionName = 'usuarios';   // Nome da colección de usuarios
$collection = $client->selectCollection($databaseName, $collectionName);

// 1. Crear un usuario
function crearUsuario($nome, $password) {
    global $collection;

    // Crear o obxecto de usuario
    $usuario = [
        'nome' => $nome,
        'password' => $password,
        'puntuacions' => [] // Array baleiro para puntuacións
    ];

    // Inserir o usuario na colección
    $result = $collection->insertOne($usuario);
    echo "Usuario creado con ID: ", $result->getInsertedId(), "<br>";
}

// 2. Ler un usuario por nome
function lerUsuario($nome) {
    global $collection;

    // Buscar o usuario polo nome
    $usuario = $collection->findOne(['nome' => $nome]);

    if ($usuario) {
        echo "Usuario encontrado: <br>";
        echo "Nome: " . $usuario['nome'] . "<br>";
        echo "Puntuacións: <br>";
        foreach ($usuario['puntuacions'] as $puntuacion) {
            echo "Puntuación: " . $puntuacion . "<br>";
        }
    } else {
        echo "Usuario non encontrado.<br>";
    }
}

// 3. Engadir unha puntuación a un usuario
function engadirPuntuacion($nome, $puntuacion) {
    global $collection;

    // Buscar o usuario polo nome
    $usuario = $collection->findOne(['nome' => $nome]);

    if ($usuario) {
        // Engadir a nova puntuación ao array de puntuacións
        $collection->updateOne(
            ['nome' => $nome], // Filtro polo nome
            ['$push' => ['puntuacions' => $puntuacion]] // Engadir puntuación ao array
        );
        echo "Puntuación engadida correctamente.<br>";
    } else {
        echo "Usuario non encontrado.<br>";
    }
}

// 4. Validar login dun usuario
function validarUsuario($nome, $password) {
    global $collection;

    // Buscar o usuario polo nome
    $usuario = $collection->findOne(['nome' => $nome]);

    if ($usuario) {
        // Comparar o password
        if ($usuario['password'] == $password) {
            echo "Login correcto.<br>";
            return true;
        } else {
            echo "Contrasinal incorrecto.<br>";
            return false;
        }
    } else {
        echo "Usuario non encontrado.<br>";
        return false;
    }
}

// 5. Eliminar un usuario por nome
function eliminarUsuario($nome) {
    global $collection;

    // Eliminar o usuario polo nome
    $deletedUser = $collection->deleteOne(['nome' => $nome]);

    if ($deletedUser->getDeletedCount() > 0) {
        echo "Usuario eliminado correctamente.<br>";
    } else {
        echo "Usuario non encontrado.<br>";
    }
}

// Exemplos de uso

// Crear un usuario (para ter un usuario base)
// crearUsuario('Pepe', '1234');

// Validar login
// validarUsuario('Pepe', '1234');

// Engadir puntuación
// engadirPuntuacion('Pepe', 7345894758645869);

// Ler usuario (ver puntuacións)
// lerUsuario('Pepe');

// Eliminar usuario
// eliminarUsuario('Pepe');
?>
