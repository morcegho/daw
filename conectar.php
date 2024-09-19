<?php
//conectar coa base de datos

$servername= "localhost";  //uso o servidor local
$username= "root";   //usuario por defecto de XAMPP
$password = ""; // nome da base de datos

//establecer a conexión
$conn = mysqli($servername, $username, $password, $dbname);

//comprobar se se tivo éxito na conexiónote
if ($conn->connect_error){
  die("Fallou a conexión: " . $conn->connect_error);
}
echo "Conectouse correctamente á base de datos"



?>
