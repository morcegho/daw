const fs = require("fs");
const readline = require("readline");

// Cargamos os datos dos usuarios e puntuacións dende arquivos locais.
let usuarios = JSON.parse(fs.readFileSync(__dirname + "/usuarios.json", "utf-8"));
let puntuacions = JSON.parse(fs.readFileSync(__dirname + "/puntuacions.json", "utf-8"));
const rl = readline.createInterface(process.stdin, process.stdout);

let usuario;  // Variable para almacenar o usuario actualmente conectado
let intentosLogin = 0; // Contador para o número de intentos fallidos de login

//----------------------------------MENÚS-------------------------------------------

function menuLogin() {
    console.log("\n--- Menú login ---");
    console.log("1. Iniciar sesión");
    console.log("2. Saír");
    rl.question("Elixe unha opción: ", (opcion) => {
        if (opcion === "1") iniciarSesion();
        else if (opcion === "2") rl.close();
        else {
            console.log("Opción inválida, volve intentalo.");
            menuLogin();
        }
    });
}

//********LOGIN ********

function iniciarSesion() {
    rl.question("Introduce o nome de usuario: ", (nomeUsuario) => {
        usuario = usuarios.find((user) => user.nome === nomeUsuario);
        if (usuario) {
            rl.question("Introduce o contrasinal: ", (contrasinal) => {
                if (usuario.contrasinal === contrasinal) {
                    console.log("Sesión iniciada correctamente");
                    intentosLogin = 0;
                    menuPrincipal();
                } else {
                    intentosLogin++;
                    console.log("Contrasinal incorrecto.");
                    verificarIntentosLogin();
                }
            });
        } else {
            intentosLogin++;
            console.log("O usuario non existe.");
            verificarIntentosLogin();
        }
    });
}

function verificarIntentosLogin() {
    if (intentosLogin >= 3) {
        console.log("Superaches o número máximo de intentos. Saíndo...");
        rl.close();
    } else {
        menuLogin();
    }
}

//*************REXISTRO **********

function rexistrarUsuario() {
    rl.question("Introduce o novo nome de usuario: ", (novoUsuario) => {
        if (usuarios.find((user) => user.nome === novoUsuario)) {
            console.log("O usuario xa existe.");
            return menuPrincipal();
        }
        rl.question("Introduce novo contrasinal: ", (novoContrasinal) => {
            const novo = { nome: novoUsuario, contrasinal: novoContrasinal, rol: "Usuario" };
            usuarios.push(novo);
            fs.writeFileSync(__dirname + "/usuarios.json", JSON.stringify(usuarios, null, 2));
            console.log("Usuario rexistrado correctamente.");
            return menuPrincipal();
        });
    });
}

//***********MENÚ PRINCIPAL **********

function menuPrincipal() {
    console.log("\n--- Menú principal ---");
    console.log("1. Xogar partida");
    console.log("2. Xogar contra a máquina");
    console.log("3. Amosar historial");

    if (usuario.rol === "Administrador") {
        console.log("4. Rexistrar novo usuario");
        console.log("5. Saír");
    } else {
        console.log("4. Saír");
    }

    rl.question("Elixe unha opción: ", (opcion) => {
        if (usuario.rol === "Administrador") {
            if (opcion === "1") xogarPartida(false);
            else if (opcion === "2") xogarPartida(true);
            else if (opcion === "3") amosarHistorial();
            else if (opcion === "4") rexistrarUsuario();
            else if (opcion === "5") rl.close();
            else {
                console.log("Opción inválida, volve intentalo.");
                menuPrincipal();
            }
        } else {
            if (opcion === "1") xogarPartida(false);
            else if (opcion === "2") xogarPartida(true);
            else if (opcion === "3") amosarHistorial();
            else if (opcion === "4") rl.close();
            else {
                console.log("Opción inválida, volve intentalo.");
                menuPrincipal();
            }
        }
    });
}

//************FUNCIÓNS PARTIDA ********

function xogarPartida(contraMaquina) {
    const cartas = ['♥', '♥', '♤', '♤', '♧', '♧'];
    let estadoCartas = new Array(6).fill(false);
    let paresEncontrados = 0;
    let puntuacionUsuario = 0;
    let puntuacionMaquina = 0;
    let turnoXogador = true;

    function mostrarCartas() {
        console.log("*************");
        for (let i = 0; i < 6; i++) {
            process.stdout.write(estadoCartas[i] ? `[${cartas[i]}] ` : "[X] ");
            if ((i + 1) % 3 === 0) console.log("");
        }
        console.log("*************");
    }

    function seleccionarCarta(callback, isMaquina = false) {
        if (isMaquina) {
            let carta1 = seleccionarAleatorio(estadoCartas);
            estadoCartas[carta1] = true;
            console.log(`A máquina seleccionou a carta ${carta1 + 1}`);
            let carta2;
            do {
                carta2 = seleccionarAleatorio(estadoCartas);
            } while (carta2 === carta1);
            estadoCartas[carta2] = true;
            console.log(`A máquina seleccionou a carta ${carta2 + 1}`);
            callback(carta1 + 1, carta2 + 1);
        } else {
            let cartasSeleccionadas = [];
            function preguntarCarta() {
                rl.question("Selecciona unha carta (1-6): ", (carta) => {
                    carta = parseInt(carta);
                    if (carta < 1 || carta > 6 || estadoCartas[carta - 1]) {
                        console.log("Carta inválida ou xa seleccionada.");
                        preguntarCarta();
                    } else {
                        cartasSeleccionadas.push(carta);
                        if (cartasSeleccionadas.length === 2) {
                            estadoCartas[cartasSeleccionadas[0] - 1] = true;
                            estadoCartas[cartasSeleccionadas[1] - 1] = true;
                            callback(cartasSeleccionadas[0], cartasSeleccionadas[1]);
                        } else {
                            preguntarCarta();
                        }
                    }
                });
            }
            preguntarCarta();
        }
    }

    function seleccionarAleatorio(estado) {
        let carta;
        do {
            carta = Math.floor(Math.random() * 6);
        } while (estado[carta]);
        return carta;
    }

    function comprobarMatch(carta1, carta2) {
        if (cartas[carta1 - 1] === cartas[carta2 - 1]) {
            console.log("Coinciden!");
            turnoXogador ? puntuacionUsuario++ : puntuacionMaquina++;
            paresEncontrados++;
            return true;
        } else {
            console.log("Non coinciden.");
            estadoCartas[carta1 - 1] = estadoCartas[carta2 - 1] = false;
            return false;
        }
    }

    function continuarXogo() {
        if (paresEncontrados === cartas.length / 2) {
            console.log(`Xogo rematado!`);
            console.log(`Puntuación Usuario: ${puntuacionUsuario}, Puntuación Máquina: ${puntuacionMaquina}`);
            console.log(puntuacionUsuario > puntuacionMaquina ? "Gañaches!" : puntuacionUsuario < puntuacionMaquina ? "A máquina gañou!" : "Empate!");
            return menuPrincipal();
        }

        mostrarCartas();

        if (turnoXogador) {
            seleccionarCarta((carta1, carta2) => {
                console.log(`Usuario destapou a carta ${carta1} e ${carta2}`);
                comprobarMatch(carta1, carta2) ? continuarXogo() : (turnoXogador = false, continuarXogo());
            });
        } else {
            seleccionarCarta((carta1, carta2) => {
                console.log(`Máquina destapou a carta ${carta1} e ${carta2}`);
                comprobarMatch(carta1, carta2) ? continuarXogo() : (turnoXogador = true, continuarXogo());
            }, true);
        }
    }

    cartas.sort(() => Math.random() - 0.5); // Mesturar cartas
    continuarXogo(); // Comezar o xogo
}

// ****** HISTORIAL DE PARTIDAS ********

function amosarHistorial() {
    console.log(`\nHistorial de partidas de ${usuario.nome}:`);
    const historialUsuario = puntuacions.filter((p) => p.usuario === usuario.nome);
    if (historialUsuario.length === 0) {
        console.log("Non hai partidas xogadas.");
    } else {
        historialUsuario.forEach((p) => {
            console.log(`Partida: ${p.data}, Puntuación: ${p.puntuacion}`);
        });
    }
    menuPrincipal();
}

// Iniciamos co menú de login.
menuLogin();
