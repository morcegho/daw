const fs = require('fs');
const readline = require('readline'); 
let usuarios = JSON.parse(fs.readFileSync(__dirname + "/usuarios.json", "utf-8"));
let puntuacions = JSON.parse(fs.readFileSync(__dirname + "/puntuacions.json", "utf-8"));


const rl = readline.createInterface(
    process.stdin,
    process.stdout
);


// console.log("*************\n [1] [2] [3]\n [4] [5] [6]\n [7] [8] [9]\n************* ");

// let usuario = "";
// MENÚ Login 

// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX 
/**
 * MEnú base que redirixe a distintas opcións, para iso agarda co await o texto que lle envía a función leeLinea
 * @returns 
 */



function menuLogin() {
    console.log("menú login:");
    console.log("1 Iniciar sesión");
    console.log("2 Rexistrar novo usuario");
    // console.log("3 Seleccionar ----");
    console.log("3 Saír")
    rl.question("Elixe unha opción: ", function (opcion) {
        switch (opcion) {
            case "1":
                rl.question("Introduce o teu nome de usuario: ", function (nomeUsuario) {
                    let usuario = usuarios.find(user => user.nome === nomeUsuario);
                    if (usuario) {
                        rl.question("Introduce o teu contrasinal: ", function (contrasinal) {
                            if (usuario.contrasinal === contrasinal) {
                                console.log("Inicio de sesión exitoso");
                                return menuPrincipal(usuario); 
                            } else {
                                console.log("Contrasinal incorrecto.");
                                return menuLogin(); 
                            }
                        });
                    } else {
                        console.log("O usuario non existe.");
                        return menuLogin(); // Volve amosar o menú de login.
                    }
                });
                break;
            case "2":
                rl.question("Introduce o novo nome de usuario: ", function (novoUsuario) {
                    rl.question("Introduce novo contrasinal: ", function (novoContrasinal) {
                        // Crea un nuevo objeto de usuario
                        let novo = { nome: novoUsuario, contrasinal: novoContrasinal };
                        // Comprueba se o usuario existe
                        if (usuarios.find(user => user.nome === novoUsuario)) {
                            console.log("O usuario xa existe.");
                            return menuLogin();
                        }
                        // Se non existe, garda o usuario
                        usuarios.push(novo);
                        fs.writeFileSync(__dirname + "/usuarios.json", JSON.stringify(usuarios, null, 2));
                        console.log("Usuario rexistrado correctamente.");
                        return menuLogin();
                    });
                });
                break;

            case "3":
                rl.close();
                break;;

        }
    })

}
function xogarPartida(){
    rl.question("Selecciona unha carta: ")
    console.log("*************\n [1] [2] [3]\n [4] [5] [6]\n [7] [8] [9]\n************* ");
}
function amosarHistorial(usuario){
    // rl.question("Selecciona unha carta: ")
    // console.log("*************\n [1] [2] [3]\n [4] [5] [6]\n [7] [8] [9]\n************* ");
    
    let puntuacion = puntuacions.find(xogador => xogador.nome === usuario.nome);
console.log(puntuacion.puntuacions)
}

function menuPrincipal(usuario) {
    console.log("menú principal:");
    console.log("1 Nova partida");
    console.log("2 Consultar historial");
    // console.log("3 Seleccionar ----");
    console.log("3 Saír")
    rl.question("Elixe unha opción: ", function (opcion) {
        switch (opcion) {
            case "1":
                xogarPartida();
                ;
            case "2":
               amosarHistorial(usuario)
               ;
                break;
            case "3":
                rl.close();
                break;;
        }
    })

}
menuLogin();

