<!-- 1. Dados 2 números diferentes, devolver el mayor. (comprobar que se introducen
números y no letras)
2. Determinar si un número entero es positivo, negativo o neutro (comprobar que se
introducen números y no letras)
3. Dado un carácter, determinar si es una vocal (Versión A: funciones propias de php.
Versión B: intentar resolver mediante sus ASCII)
4. Determinar si un número es múltiplo de 3 y de 5
5. Dados 3 números enteros, devolver el mayor (comprobar que el número introducido
es entero)
6. Un restaurante ofrece un descuento del 10% para consumos hasta 100€ y un
descuento del 20% para consumos mayores. Para ambos casos, se aplica un
impuesto del 19%. Determinar la cantidad a pagar que le corresponde al descuento,
el impuesto y el importe a pagar.

7. Dado un número entero de un dígito, devolver ese mismo número expresado en
letras
8. Dado un rango de números enteros, obtener la cantidad de números pares que
contiene
9. Dado un número N, obtener la cantidad desde 0 hasta N de múltiplos de 5
10. Dado un número determinar:
a. Cuantos dígitos tiene
b. Cuantos dígitos pares tiene
c. El dígito mayor
11. Dado un número N, devuelve el inverso de ese número
12. Crear un algoritmo que indique si un número es un cubo perfecto o no lo es
13. Dado un número N, obtener la suma de pares y de impares de los primeros enteros
positivos.
14. Dado un número M y un rango, encontrar cuántos múltiplos de M hay en el rango de
números enteros
15. Dado un número, determinar si es primo. (Hacer dos versiones: una con for y otra
con while)
16. Mostrar las letras desde la a hasta la z. Utilizar un bucle for y como variable de
control, un carácter.
17. Crear una página de login con php. Debe ser capaz de diferenciar si el usuario el es
(o no) correcto y si se ha introducido la clave correctamente.
18. Crea un formulario y su validación.
19. Crea una web, que reciba los años de nacimiento de varios alumnos. El programa
debe ser capaz de ordenarlos de menor a mayor, permitir nuevos años y buscar un
año concreto así como eliminarlo diciendo si existe o no existe. También debe ser
capaz de leer el array diciendo que posición ocupa cada elemento en su interior.
20. Crea una web que reciba una palabra y determine si es palíndroma. Una palabra
palíndroma es aquella que se lee del derecho y del revés de la misma manera
21. Crea una web que reciba un DNI y compruebe que lo que se ha introducido es
correcto.
a. MOD: modifica el ejercicio para que al poner el DNI, se ponga directamente
la letra.
22. Crear una página que reciba una palabra y determine su composición: todo
mayúsculas, todo minúsculas, mezcla (contar mayúsculas y minúsculas), cuantos
números si los tiene...
23. Crear una expresión regular que valide una fecha en formato XX/XX/XXXX
24. Crear una expresión regular que valide un correo electrónico -->




<!-- 1. Dados 2 números diferentes, devolver el mayor. (comprobar que se introducen
números y no letras) -->
<?php
echo "<strong>Consulta: 1. Dados 2 números diferentes, devolver el mayor. (comprobar que se introducen
números y no letras) </strong> Introducido: 1 e 6
<br>";
function buscaMaior($num1, $num2)
{
    if (is_numeric($num1) && is_numeric($num2)) {
        if ($num1 > $num2) {
            return "$num1 é maior";
        } else {
            return "$num2 é maior";
        }
    }
    return "Un dos dous elementos non é un número";
}


echo buscaMaior(1, 6); // Debe mostrar 6 é maior

echo "<br><strong>Consulta: 2. Determinar si un número entero es positivo, negativo o neutro (comprobar que se
introducen números y no letras) </strong> <br>";

function comprobarNum($num1)
{
    if (is_string($num1)) {
        return "$num1 non é un número";
    } else if (is_numeric($num1)) {
        if ($num1 > 0) {
            return "$num1 é un número positivo";
        } else {
            return "$num1 non é un número positivo";
        }
    }
    ;

}

echo comprobarNum('p');

echo "<br><strong>Consulta: 3 Dado un carácter, determinar si es una vocal (Versión A: funciones propias de php.
Versión B: intentar resolver mediante sus ASCII) </strong> <br>";

function consulta3($caracter)
{
    if (is_string($caracter)) {
        //     return "$num1 non é un número";
        // } elseif (is_numeric($num1)) {
        //     if ($num1 > 0) {
        //         return "$num1 é un número positivo";
        //     } else {
        //         return "$num1 non é un número positivo";
        //     }
    }
    ;

}

// echo consulta3();

echo "<br><strong>Consulta: 4. Determinar si un número es múltiplo de 3 y de 5
</strong> <br>";

function multiplo3e5($numero)
{
    if (is_numeric($numero)) {

        if ($numero % 3 === 0) {
            return "$numero é un múltiplo de 3";
        } else if ($numero % 5 === 0) {
            return "$numero é un múltiplo de 5";
        } else {
            return "$numero non é múltiplo de 3 nin 5";
        }
    }
    ;

}

echo multiplo3e5(25);
echo "<br><strong>Consulta: 5. Dados 3 números enteros, devolver el mayor (comprobar que el número introducido
es entero)
</strong> <br>";

function consulta3numeros($num1, $num2, $num3)
{
    $maior = 0;
    if (is_int($num1) && is_int($num2) && is_int($num3)) {

        if ($num1 > $num2) {
            $maior = $num1;
        } else {
            $maior = $num2;
        }
        if ($maior > $num3) {
            return "$maior é o número máis alto";
        }
        return "$num3 é o número máis alto";
    }
    return "Un dos dous elementos non é un número";
}

echo consulta3numeros(1222, 22, 5);

// echo consulta3();
echo "<br><strong>Consulta: 6. Un restaurante ofrece un descuento del 10% para consumos hasta 100€ y un
descuento del 20% para consumos mayores. Para ambos casos, se aplica un
impuesto del 19%. Determinar la cantidad a pagar que le corresponde al descuento,
el impuesto y el importe a pagar.  </strong> <br>";

function consulta6($consumo)
{
    $consumoGravado = $consumo + ($consumo*19)/100;
    $prezo = 0;
    //consumo co imposto do 19%
    // return "consumo gravado de $consumo é $consumoGravado";
    if ($consumo<101) {
        $prezo = $consumoGravado - $consumo*10/100;
        return "paga $prezo";
    }
    $prezo = $consumoGravado - $consumo*20/100;
    return "paga $prezo";

}
echo consulta6(100);
//-------------------------


// 7. Dado un número entero de un dígito, devolver ese mismo número expresado en letras
// 8. Dado un rango de números enteros, obtener la cantidad de números pares que
// contiene
// 9. Dado un número N, obtener la cantidad desde 0 hasta N de múltiplos de 5
// 10. Dado un número determinar:
// a. Cuantos dígitos tiene
// b. Cuantos dígitos pares tiene
// c. El dígito mayor
// 11. Dado un número N, devuelve el inverso de ese número
// 12. Crear un algoritmo que indique si un número es un cubo perfecto o no lo es
// 13. Dado un número N, obtener la suma de pares y de impares de los primeros enteros
// positivos.
// 14. Dado un número M y un rango, encontrar cuántos múltiplos de M hay en el rango de
// números enteros

echo "<br><strong>Consulta: 7. Dado un número entero de un dígito, 
devolver ese mismo número expresado en letras  </strong> <br>";

function consulta7()
{
echo "<br><strong>SEN FACER Consulta: 7";

}
echo consulta7();


echo "<br><strong>Consulta: 8. Dado un rango de números enteros, 
obtener la cantidad de números pares que contiene";
function consulta8($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 8";


}
echo consulta8(100);
echo "<br><strong> 9. Dado un número N, obtener la cantidad desde 0 hasta N de múltiplos de 5";
function consulta9($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 9";


}
echo consulta9(100);


echo "<br><strong>Consulta: 10 Dado un número determinar:
// a. Cuantos dígitos tiene
// b. Cuantos dígitos pares tiene
// c. El dígito mayor";
function consulta10($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 10";


}
echo consulta10(100);


echo "<br><strong>Consulta:11. Dado un número N, devuelve el inverso de ese número</strong> <br>";

function consulta11($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 11";
}
echo consulta11(100);


echo "<br><strong>Consulta: 12. Crear un algoritmo que indique si un número es un cubo perfecto o no lo es</strong> <br>";

function consulta12($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 12";
}
echo consulta12(100);
echo "<br><strong>Consulta:  Dado un número N, obtener la suma de pares y de impares de los primeros enteros positivos.</strong> <br>";

function consulta13($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 13";
}
echo consulta13(100);
echo "<br><strong>Consulta: Dado un número M y un rango, encontrar cuántos múltiplos de M hay en el rango de
números enteros</strong> <br>";
function consulta14($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 14";
}
echo consulta14(100);
echo "<br><strong>Consulta: Dado un número M y un rango, encontrar cuántos múltiplos de M hay en el rango de
números enteros</strong> <br>";

function consulta15($consumo)
{
    echo "<br><strong>SEN FACER Consulta: 15";
}
echo consulta15(100);




// function consulta3($caracter)
// {
//     if (is_string($caracter)) {
//     return "$num1 non é un número";
// } elseif (is_numeric($num1)) {
//     if ($num1 > 0) {
//         return "$num1 é un número positivo";
//     } else {
//         return "$num1 non é un número positivo";
//     }
//     }
//     ;

// }

// echo consulta3();
?>