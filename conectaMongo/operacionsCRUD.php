<?php
// Habilitar reporte de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
use MongoDB\Client;

$uri = 'mongodb+srv://mvilceb:noliu111@cluster0.iqwdukh.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
$client = new Client($uri);
$databaseName = 'mdbPHP'; // Base de datos
$collectionName = 'usuarios';   // Colección de usuarios
$collection = $client->selectCollection($databaseName, $collectionName);

// 1. Crear un usuario
function crearUsuario($nome, $correo, $contrasinal) {
    global $collection;
    
    // Encriptar a contrasinal (pódese desactivar se non quixer encriptar)
    // $hashedPassword = password_hash($contrasinal, PASSWORD_DEFAULT);
    
    // Crear o documento do usuario
    $documento = [
        'nome' => $nome,
        'correo' => $correo,
        'contrasinal' => $contrasinal, // Non se está encriptando
        'puntuacions' => [], // Iniciar un array baleiro de puntuacións
        'data_rexistro' => new MongoDB\BSON\UTCDateTime() // Data actual de rexistro
    ];
    
    // Inserir o usuario na colección
    $result = $collection->insertOne($documento);
    echo "Usuario creado con ID: ", $result->getInsertedId(), "<br>";
}

// 2. Ler un usuario por correo
function lerUsuario($correo) {
    global $collection;
    
    // Buscar o usuario polo correo
    $usuario = $collection->findOne(['correo' => $correo]);
    
    if ($usuario) {
        echo "Usuario encontrado: <br>";
        echo "Nome: " . $usuario['nome'] . "<br>";
        echo "Correo: " . $usuario['correo'] . "<br>";
        echo "Data de rexistro: " . $usuario['data_rexistro']->toDateTime()->format('Y-m-d H:i:s') . "<br>";
        echo "Puntuacións: <br>";
        // Mostrar todas as puntuacións
        foreach ($usuario['puntuacions'] as $puntuacion) {
            echo "Puntuación: " . $puntuacion['puntuacion'] . " (Data: " . $puntuacion['data']->toDateTime()->format('Y-m-d H:i:s') . ")<br>";
        }
    } else {
        echo "Usuario non encontrado.<br>";
    }
}

// 3. Engadir unha puntuación a un usuario
function engadirPuntuacion($correo, $puntuacion) {
    global $collection;
    
    // Buscar o usuario polo correo
    $usuario = $collection->findOne(['correo' => $correo]);
    
    if ($usuario) {
        // Engadir a nova puntuación ao array de puntuacións
        $novaPuntuacion = [
            'puntuacion' => $puntuacion,
            'data' => new MongoDB\BSON\UTCDateTime() // Data da partida
        ];
        
        // Actualizar o documento do usuario
        $updatedUser = $collection->updateOne(
            ['correo' => $correo], // Filtro polo correo
            ['$push' => ['puntuacions' => $novaPuntuacion]] // Engadir ao array de puntuacións
        );
        
        echo "Puntuación engadida correctamente.<br>";
    } else {
        echo "Usuario non atopado.<br>";
    }
}

// 4. Eliminar un usuario por correo
function eliminarUsuario($correo) {
    global $collection;
    
    // Eliminar o usuario polo correo
    $deletedUser = $collection->deleteOne(['correo' => $correo]);
    
    if ($deletedUser->getDeletedCount() > 0) {
        echo "Usuario eliminado correctamente.<br>";
    } else {
        echo "Usuario non atopado.<br>";
    }
}

// 5. Validar a contrasinal dun usuario
function validarContrasinal($correo, $contrasinal) {
    global $collection;
    
    // Buscar o usuario polo correo
    $usuario = $collection->findOne(['correo' => $correo]);
    
    if ($usuario) {
        // Verificar o contrasinal
        // NOTA: No caso de non usar password_hash, isto non será necesario.
        if ($usuario['contrasinal'] == $contrasinal) {
            echo "O contrasinal é correcto.<br>";
        } else {
            echo "O contrasinal é incorrecto.<br>";
        }
    } else {
        echo "Usuario non encontrado.<br>";
    }
}

// PROBAS ---  Exemplos de uso

// Crear usuario
// crearUsuario('qwerty', 'juan@example.com', 'contrasina123');

// Engadir puntuacións
// engadirPuntuacion('qwerty@exemplo.com', 1200);
// engadirPuntuacion('qwerty@exemplo.com', 1300);

// Ler usuario (ver puntuacións)
// lerUsuario('qwerty@exemplo.com');

// Validar contrasinal
// validarContrasinal('qwerty@exemplo.com', 'contrasina123');

// Eliminar usuario
// eliminarUsuario('qwerty@exemplo.com');
?>
