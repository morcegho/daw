const fs = require("fs");
const readline = require("readline");

let usuarios = JSON.parse(fs.readFileSync(__dirname + "/usuarios.json", "utf-8"));
let puntuacions = JSON.parse(fs.readFileSync(__dirname + "/puntuacions.json", "utf-8"));
const rl = readline.createInterface(process.stdin, process.stdout);

let usuario;
let intentosLogin = 0;

function menuLogin() {
    console.log("\n--- Menú login ---");
    console.log("1. Iniciar sesión");
    console.log("2. Saír");
    rl.question("Elixe unha opción: ", (opcion) => {
        switch (opcion) {
            case "1":
                iniciarSesion();
                break;
            case "2":
                rl.close();
                break;
            default:
                console.log("Opción inválida, volve intentalo.");
                menuLogin();
                break;
        }
    });
}

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

function menuPrincipal() {
    console.log("\n--- Menú principal ---");
    console.log("1. Xogar partida (Un xogador)");
    console.log("2. Xogar contra a máquina");
    console.log("3. Amosar historial");

    if (usuario.rol === "Administrador") {
        console.log("4. Rexistrar novo usuario");
        console.log("5. Saír");
    } else {
        console.log("4. Saír");
    }

    rl.question("Elixe unha opción: ", (opcion) => {
        switch (usuario.rol) {
            case "Administrador":
                switch (opcion) {
                    case "1":
                        xogarPartida(false); // Modo un xogador
                        break;
                    case "2":
                        xogarPartida(true); // Modo contra a máquina
                        break;
                    case "3":
                        amosarHistorial();
                        break;
                    case "4":
                        rexistrarUsuario();
                        break;
                    case "5":
                        rl.close();
                        break;
                    default:
                        console.log("Opción inválida, volve intentalo.");
                        menuPrincipal();
                        break;
                }
                break;
            default:
                switch (opcion) {
                    case "1":
                        xogarPartida(false); // Modo un xogador
                        break;
                    case "2":
                        xogarPartida(true); // Modo contra a máquina
                        break;
                    case "3":
                        amosarHistorial();
                        break;
                    case "4":
                        rl.close();
                        break;
                    default:
                        console.log("Opción inválida, volve intentalo.");
                        menuPrincipal();
                        break;
                }
                break;
        }
    });
}

function xogarPartida(contraMaquina) {
    const cartas = ['♥', '♥', '♤', '♤', '♧', '♧'];
    let estadoCartas = new Array(6).fill(false);
    let paresEncontrados = 0;
    let puntuacionUsuario = 0;
    let puntuacionMaquina = 0;
    let turnoXogador = true;
    let cartasSeleccionadas = [];

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
            function preguntarCarta() {
                rl.question("Selecciona unha carta (1-6): ", (carta) => {
                    carta = parseInt(carta);
                    if (carta < 1 || carta > 6 || estadoCartas[carta - 1] || cartasSeleccionadas.includes(carta)) {
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

    function verificarCoincidencia(carta1, carta2) {
        if (cartas[carta1 - 1] === cartas[carta2 - 1]) {
            console.log("Coinciden!");
            paresEncontrados++;
            if (turnoXogador) {
                puntuacionUsuario++;
            } else {
                puntuacionMaquina++;
            }
            if (paresEncontrados === 3) {
                console.log(`Xogo rematado! Puntuación Usuario: ${puntuacionUsuario}, Puntuación Máquina: ${puntuacionMaquina}`);
                return;
            }
            cartasSeleccionadas = []; // Reiniciar selección de cartas.
            mostrarCartas();
            if (!turnoXogador && contraMaquina) {
                setTimeout(() => seleccionarCarta(turnoMaquina, true), 1000); // Despos de 1 segundo, a máquina selecciona.
            } else {
                turnoXogador = false; // Cambiar turno
                seleccionarCarta((carta1, carta2) => {
                    verificarCoincidencia(carta1, carta2);
                    turnoXogador = true; // Cambiar de novo ao usuario
                });
            }
        } else {
            console.log("Non coinciden.");
            setTimeout(() => {
                estadoCartas[carta1 - 1] = false; // tapa carta 1
                estadoCartas[carta2 - 1] = false; // tapa carta 2
                cartasSeleccionadas = []; // Reiniciar selección de cartas
                mostrarCartas(); // Mostrar estado actualizado
                turnoXogador = !turnoXogador; // Cambiar de turno
                if (!turnoXogador && contraMaquina) {
                    setTimeout(() => seleccionarCarta(turnoMaquina, true), 1000); 
                    // A máquina selecciona despois de 1 segundo
                } else {
                    seleccionarCarta((carta1, carta2) => {
                        verificarCoincidencia(carta1, carta2);
                    });
                }
            }, 1000);
        }
    }

    mostrarCartas();
    seleccionarCarta((carta1, carta2) => {
        verificarCoincidencia(carta1, carta2);
    });
}

// Función para mostrar historial
function amosarHistorial() {
  console.log(`Historial de partidas de ${usuario.nome}:`);
  const historialUsuario = puntuacions.filter((p) => p.usuario === usuario.nome);
  if (historialUsuario.length === 0) {
    console.log("Non hai partidas rexistradas pra este usuario.");
  } else {
    historialUsuario.forEach((partida, index) => {
      console.log(`Partida ${index + 1}: ${partida.puntos} puntos`);
    });
  }
  menuPrincipal();
}


menuLogin();
