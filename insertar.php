<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "primeira_base_de_datos";

//conexión

$conn = new mysqli($servername, $username, $password, $dbname);

//Comprobar
if ($conn -> connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

//meter metadatos
$sql = INSERT INTO usuarios (nome, email) VALUES ('Pepiño'. 'pepe@pepon.com');


if ($conn->query($sql) === TRUE) {
  echo "Rexistrouse o novo usuario correctamente";
}
//Fechar conexiónote
$conn->close();
 ?>
