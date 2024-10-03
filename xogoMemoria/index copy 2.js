const fs = require("fs");
const readline = require("readline");

// Cargamos os datos dos usuarios e puntuacións dende arquivos locais.
let usuarios = JSON.parse(fs.readFileSync(__dirname + "/usuarios.json", "utf-8"));
let puntuacions = JSON.parse(fs.readFileSync(__dirname + "/puntuacions.json", "utf-8"));
const rl = readline.createInterface(process.stdin, process.stdout);

let usuario;  // Variable para almacenar o usuario actualmente conectado
let intentosLogin = 0; // Contador para o número de intentos fallidos de login

//----------------------------------MENÚS-------------------------------------------
// Função para o menú de login
function menuLogin() {
  console.log("\n--- Menú login ---");
  console.log("1. Iniciar sesión");
  console.log("2. Saír");

  // Pedimos ao usuario que elixa unha opción do menú de login
  rl.question("Elixe unha opción: ", (opcion) => {
    switch (opcion) {
      case "1":
        iniciarSesion(); // Comezamos o proceso de inicio de sesión
        break;
      case "2":
        rl.close(); // Pechamos a interface, rematando o programa
        break;
      default:
        console.log("Opción inválida, volve intentalo."); // Mensaxe se a opción non é válida
        menuLogin(); // Volvemos amosar o menú
    }
  });
}

//********LOGIN ********
// Função para iniciar sesión co usuario existente
function iniciarSesion() {
  rl.question("Introduce o nome de usuario: ", (nomeUsuario) => {
    usuario = usuarios.find((user) => user.nome === nomeUsuario);
    if (usuario) {
      // Se o usuario existe, solicitamos o contrasinal
      rl.question("Introduce o contrasinal: ", (contrasinal) => {
        if (usuario.contrasinal === contrasinal) {
          console.log("Sesión iniciada correctamente");
          intentosLogin = 0; // Restablecemos o contador de intentos
          menuPrincipal(); // Dirixímonos ao menú principal
        } else {
          intentosLogin++; // Incrementamos o contador se o contrasinal non é correcto
          console.log("Contrasinal incorrecto.");
          verificarIntentosLogin(); // Verificamos se superamos o número de intentos permitidos
        }
      });
    } else {
      intentosLogin++; // Incrementamos o contador se o usuario non existe
      console.log("O usuario non existe.");
      verificarIntentosLogin(); // Verificamos se superamos o número de intentos permitidos
    }
  });
}

// Função para verificar se superamos o número de intentos de login permitidos
function verificarIntentosLogin() {
  if (intentosLogin >= 3) {
    console.log("Superaches o número máximo de intentos. Saíndo...");
    rl.close(); // Pechamos a interface
  } else {
    menuLogin(); // Amosamos de novo o menú de login
  }
}

//*************REXISTRO **********
// Função para rexistrar un novo usuario (dispoñible só para administradores)
function rexistrarUsuario() {
  rl.question("Introduce o novo nome de usuario: ", (novoUsuario) => {
    if (usuarios.find((user) => user.nome === novoUsuario)) {
      console.log("O usuario xa existe."); // Verificamos se o usuario xa existe
      return menuPrincipal(); // Se xa existe, retornamos ao menú principal
    }
    rl.question("Introduce novo contrasinal: ", (novoContrasinal) => {
      const novo = { nome: novoUsuario, contrasinal: novoContrasinal, rol: "Usuario" }; // Novo usuario terá o rol "Usuario"
      usuarios.push(novo); // Engadimos o novo usuario á lista de usuarios
      fs.writeFileSync(__dirname + "/usuarios.json", JSON.stringify(usuarios, null, 2)); // Gardamos os usuarios no arquivo
      console.log("Usuario rexistrado correctamente.");
      return menuPrincipal(); // Volvemos ao menú principal
    });
  });
}

//***********MENÚ PRINCIPAL **********
// Função para mostrar o menú principal logo de iniciar sesión
function menuPrincipal() {
  console.log("\n--- Menú principal ---");
  console.log("1. Xogar partida");
  console.log("2. Xogar contra a máquina");
  console.log("3. Amosar historial");

  // Engadir a opción de rexistrar un novo usuario só se o usuario é administrador
  if (usuario.rol === "Administrador") {
    console.log("4. Rexistrar novo usuario");
    console.log("5. Saír");
  } else {
    console.log("4. Saír");
  }

  rl.question("Elixe unha opción: ", (opcion) => {
    if (usuario.rol === "Administrador") {
      // Opcións dispoñibles para o administrador
      switch (opcion) {
        case "1":
          xogarPartida(false); // Xogar partida entre dous xogadores humanos
          break;
        case "2":
          xogarPartida(true); // Xogar contra a máquina
          break;
        case "3":
          amosarHistorial(); // Amosar o historial de partidas do usuario actual
          break;
        case "4":
          rexistrarUsuario(); // Rexistrar un novo usuario
          break;
        case "5":
          rl.close(); // Pechar a interface, rematando o programa
          break;
        default:
          console.log("Opción inválida, volve intentalo.");
          menuPrincipal(); // Se a opción é inválida, amosar de novo o menú principal
      }
    } else {
      // Opcións dispoñibles para un usuario regular
      switch (opcion) {
        case "1":
          xogarPartida(false); // Xogar partida entre dous xogadores humanos
          break;
        case "2":
          xogarPartida(true); // Xogar contra a máquina
          break;
        case "3":
          amosarHistorial(); // Amosar o historial de partidas do usuario actual
          break;
        case "4":
          rl.close(); // Pechar a interface, rematando o programa
          break;
        default:
          console.log("Opción inválida, volve intentalo.");
          menuPrincipal(); // Se a opción é inválida, amosar de novo o menú principal
      }
    }
  });
}

//************FUNCIÓNS PARTIDA ********
// Função para xogar unha partida de memoria
// function xogarPartida(contraMaquina) {
//   const cartas = ['♥', '♥', '♤', '♤', '♧', '♧']; // Creamos as cartas en pares
//   let estadoCartas = new Array(6).fill(false); // Creamos un array para saber que cartas están descubertas
//   let paresEncontrados = 0; // Número de pares atopados

//   let turnoXogador = true; // Controla o turno (true para usuario, false para máquina)
//   let puntuacionUsuario = 0; // Puntuación do usuario
//   let puntuacionMaquina = 0; // Puntuación da máquina

//   // Função para amosar o estado actual das cartas
//   function mostrarCartas() {
//     console.log("*************");
//     for (let i = 0; i < 6; i++) {
//       process.stdout.write(estadoCartas[i] ? `[${cartas[i]}] ` : "[X] ");
//       if ((i + 1) % 3 === 0) console.log("");
//     }
//     console.log("*************");
//   }

  
//     // Função para seleccionar unha carta, ben polo usuario ou pola máquina
//     function seleccionarCarta(callback, isMaquina = false) {
//       if (isMaquina) {
//           let carta;
//           do {
//               carta = Math.floor(Math.random() * 6); // Selecciona unha carta aleatoriamente (0 a 5)
//           } while (estadoCartas[carta]); // Asegúrate de non seleccionar unha carta xa descuberta

//           console.log(`A máquina seleccionou a carta ${carta + 1}`); // Mostra a carta seleccionada (1 a 6)
//           estadoCartas[carta] = true; // Marca a carta como descuberta
//           callback(carta + 1); // Chama ao callback con a carta (1 a 6)
//       } else {
//           rl.question("Selecciona unha carta (1-6): ", (carta) => {
//               carta = parseInt(carta);
//               if (carta < 1 || carta > 6 || estadoCartas[carta - 1]) {
//                   console.log("Carta inválida ou xa seleccionada.");
//                   return seleccionarCarta(callback); // Volve chamar á función se a carta é inválida
//               }
//               estadoCartas[carta - 1] = true; // Marca a carta como descuberta
//               callback(carta); // Chama ao callback con a carta seleccionada
//           });
//       }
//   }

//   // Função para comprobar se dúas cartas coinciden
//   function comprobarMatch(carta1, carta2) {
//     if (cartas[carta1 - 1] === cartas[carta2 - 1]) {
//       console.log("Coinciden!");
//       paresEncontrados++;
//       if (turnoXogador) {
//         puntuacionUsuario++;
//       } else {
//         puntuacionMaquina++;
//       }
//       return true;
//     } else {
//       console.log("Non coinciden.");
//       estadoCartas[carta1 - 1] = false;
//       estadoCartas[carta2 - 1] = false;
//       return false;
//     }
//   }

//   // Função para continuar xogando ou rematar a partida
//   function continuarXogo() {
//     if (paresEncontrados === cartas.length / 2) {
//         console.log(`Xogo rematado!`);
//         console.log(`Puntuación Usuario: ${puntuacionUsuario}, Puntuación Máquina: ${puntuacionMaquina}`);
//         if (puntuacionUsuario > puntuacionMaquina) {
//             console.log("Gañaches!");
//         } else if (puntuacionUsuario < puntuacionMaquina) {
//             console.log("A máquina gañou!");
//         } else {
//             console.log("Empate!");
//         }
//         menuPrincipal(); // Volvemos ao menú principal despois do xogo
//     } else {
//         mostrarCartas(); // Amosamos as cartas actuais

//         if (turnoXogador) {
//             // Se é o turno do xogador humano
//             seleccionarCarta((carta1) => {
//                 mostrarCartas();
//                 seleccionarCarta((carta2) => {
//                     const match = comprobarMatch(carta1, carta2);
//                     if (!match) {
//                         turnoXogador = false; // Se non hai coincidencia, cambia de turno para a máquina
//                     }
//                     setTimeout(continuarXogo, 1000); // Continúa xogando despois de 1 segundo
//                 });
//             });
//         } else {
//             // Se é o turno da máquina
//             seleccionarCarta((carta1) => {
//                 console.log(`A máquina destapou a carta ${carta1}`);
//                 mostrarCartas();
//                 seleccionarCarta((carta2) => {
//                     console.log(`A máquina destapou a carta ${carta2}`);
//                     const match = comprobarMatch(carta1, carta2);
//                     if (match) {
//                         console.log("As cartas coinciden!");
//                     } else {
//                         console.log("As cartas non coinciden.");
//                         turnoXogador = true; // Se non hai coincidencia, cambia de turno para o xogador humano
//                     }
//                     setTimeout(continuarXogo, 1000); // Continúa xogando despois de 1 segundo
//                 }, true);
//             }, true);
//         }
//     }
// }



//   cartas.sort(() => Math.random() - 0.5); // Mesturamos as cartas de forma aleatoria
//   continuarXogo(); // Comezamos o xogo
// }
// Função para xogar unha partida de memoria
function xogarPartida(contraMaquina) {
  const cartas = ['♥', '♥', '♤', '♤', '♧', '♧']; // Cartas en parellas
  let estadoCartas = new Array(6).fill(false); // Estado das cartas (tapadas)
  let paresEncontrados = 0; // Contador de pares
  let puntuacionUsuario = 0; // Puntuación do usuario
  let puntuacionMaquina = 0; // Puntuación da máquina
  let turnoXogador = true; // Control do turno

  // Función para mostrar o estado das cartas
  function mostrarCartas() {
      console.log("*************");
      for (let i = 0; i < 6; i++) {
          process.stdout.write(estadoCartas[i] ? `[${cartas[i]}] ` : "[X] ");
          if ((i + 1) % 3 === 0) console.log(""); // Salto de liña cada 3
      }
      console.log("*************");
  }

  // Función para seleccionar unha carta
  function seleccionarCarta(callback, isMaquina = false) {
      if (isMaquina) {
          let carta1, carta2;

          // Seleccionar a primeira carta
          do {
              carta1 = Math.floor(Math.random() * 6); // Selección aleatoria
          } while (estadoCartas[carta1]); // Repetir se está descuberta

          estadoCartas[carta1] = true; // Marcamos a primeira carta como descuberta
          console.log(`A máquina seleccionou a carta ${carta1 + 1}`);

          // Seleccionar a segunda carta
          do {
              carta2 = Math.floor(Math.random() * 6); // Selección aleatoria
          } while (estadoCartas[carta2] || carta2 === carta1); // Asegurarse de que non sexa a mesma

          estadoCartas[carta2] = true; // Marcamos a segunda carta como descuberta
          console.log(`A máquina seleccionou a carta ${carta2 + 1}`);
          callback(carta1 + 1, carta2 + 1); // Chamamos ao callback
      } else {
          rl.question("Selecciona unha carta (1-6): ", (carta) => {
              carta = parseInt(carta);
              if (carta < 1 || carta > 6 || estadoCartas[carta - 1]) {
                  console.log("Carta inválida ou xa seleccionada.");
                  return seleccionarCarta(callback); // Pedimos de novo
              }
              estadoCartas[carta - 1] = true; // Marcamos a carta como descuberta
              callback(carta); // Chamamos ao callback
          });
      }
  }

  // Función para comprobar coincidencias
  function comprobarMatch(carta1, carta2) {
      if (cartas[carta1 - 1] === cartas[carta2 - 1]) {
          console.log("Coinciden!");
          turnoXogador ? puntuacionUsuario++ : puntuacionMaquina++;
          paresEncontrados++; // Aumentar pares atopados
          return true; // Coinciden
      } else {
          console.log("Non coinciden.");
          estadoCartas[carta1 - 1] = estadoCartas[carta2 - 1] = false; // Volta a tapar
          return false; // Non coinciden
      }
  }

  // Función para continuar o xogo
  function continuarXogo() {
      if (paresEncontrados === cartas.length / 2) { // Comprobar se rematou
          console.log(`Xogo rematado!`);
          console.log(`Puntuación Usuario: ${puntuacionUsuario}, Puntuación Máquina: ${puntuacionMaquina}`);
          console.log(puntuacionUsuario > puntuacionMaquina ? "Gañaches!" : puntuacionUsuario < puntuacionMaquina ? "A máquina gañou!" : "Empate!");
          return menuPrincipal(); // Volver ao menú principal
      }

      mostrarCartas(); // Mostrar o estado actual

      // Función para o turno do xogador ou da máquina
      if (turnoXogador) {
          seleccionarCarta((carta1, carta2) => {
              console.log(`Usuario destapou a carta ${carta1} e ${carta2}`);
              if (comprobarMatch(carta1, carta2)) {
                  continuarXogo(); // Continuar xogando
              } else {
                  turnoXogador = false; // Cambiar turno á máquina
                  continuarXogo(); // Continuar xogando
              }
          });
      } else {
          seleccionarCarta((carta1, carta2) => {
              console.log(`Máquina destapou a carta ${carta1} e ${carta2}`);
              if (comprobarMatch(carta1, carta2)) {
                  continuarXogo(); // Continuar xogando
              } else {
                  turnoXogador = true; // Cambiar turno ao usuario
                  continuarXogo(); // Continuar xogando
              }
          }, true); // Indicar que é a máquina
      }
  }

  cartas.sort(() => Math.random() - 0.5); // Mesturar cartas
  continuarXogo(); // Comezar o xogo
}


// ****** HISTORIAL DE PARTIDAS ********
// Função para amosar o historial de partidas do usuario conectado
function amosarHistorial() {
  console.log(`\nHistorial de partidas de ${usuario.nome}:`);
  const historialUsuario = puntuacions.filter((p) => p.usuario === usuario.nome);
  if (historialUsuario.length === 0) {
    console.log("Non hai partidas rexistradas pra este usuario.");
  } else {
    historialUsuario.forEach((partida, index) => {
      console.log(`Partida ${index + 1}: ${partida.puntos} puntos`);
    });
  }
  menuPrincipal(); // Volvemos ao menú principal
}

// Chamamos ao menú de login ao comezar o programa
menuLogin();
