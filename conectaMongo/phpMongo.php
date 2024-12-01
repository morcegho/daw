<?php
// Habilitar reporte de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
use MongoDB\Client;
$uri = 'mongodb+srv://mvilceb:noliu111@cluster0.iqwdukh.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
$client = new Client($uri);
$databaseName = 'mdbPHP'; // Se non existe, crea
$collectionName = 'coleccionPHP';   // Se non existe, crea
$collection = $client->selectCollection($databaseName, $collectionName);
// Inserta un documento para crear a colección se non existe
$document = [
    'nome' => 'Proba',
    'correo' => 'proba@exmplo.com',
    'idade' => 25
];
$result = $collection->insertOne($document);
echo "Base de datos e colección listas. Documento insertado con ID: ", $result->getInsertedId();
?>
