<?php
conexionphp();
function conexionphp(){
    $server = 'localhost';
    $user = 'root';
    $pass = 'Contrasinal111';
    $db = 'nova_base';
    $conectar = mysqli_connect($server, $user, $pass, $db)or die ("Erro conectando");
    echo "Conectouse correctamente á base de datos";
//meter metadatos
// $sql = INSERT INTO usuarios (dni, nome, primeiro_apelido, segundo_apelido, enderezo, telefono, nacemento) VALUES ('Pepiño', 'prz', 'rgz', 'dfsdfs sdfs fs dfs', '986766666', '05/06/1990');


// if ($conn->query($sql) === TRUE) {
//   echo "Rexistrouse o novo usuario correctamente";
// }
    return $conectar;
}



?>



<!-- selecciono base de datos e creo táboa
 use nova_base;
CREATE TABLE usuarios (
dni VARCHAR(9) NOT NULL PRIMARY KEY,
nome VARCHAR(30) NOT NULL,
primeiro_apelido VARCHAR(30) NOT NULL,
segundo_apelido VARCHAR(30) NOT NULL,
enderezo VARCHAR(99) NOT NULL,
telefono VARCHAR(30) NOT NULL,
nacemento TIMESTAMP NOT NULL
/**email VARCHAR(50),
reg_date TIMESTAMP**/
); -->