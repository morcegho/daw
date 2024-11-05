const fs = require("fs");
const readline = require("readline");

let usuarios = JSON.parse(fs.readFileSync(__dirname + "/usuarios.json", "utf-8"));
let puntuacions = JSON.parse(fs.readFileSync(__dirname + "/puntuacions.json", "utf-8"));
const rl = readline.createInterface(process.stdin, process.stdout);

let usuario;

//----------------------------------MENÚS-------------------------------------------
//*******INICIO *************/
function menuLogin() {
  console.log("Menú login:");
  console.log("1 Iniciar sesión");
  console.log("2 Saír");
  rl.question("Elixe unha opción: ", function (opcion) {
    switch (opcion) {
      case "1":
        iniciarSesion();
        break;
      case "2":
        rl.close();
        break;
    }
  });
}
//********LOGIN ***************/
function iniciarSesion() {
  rl.question("Introduce o nome de usuario: ", function (nomeUsuario) {
    usuario = usuarios.find((user) => user.nome === nomeUsuario);
    if (usuario) {
      rl.question("Introduce o contrasinal: ", function (contrasinal) {
        if (usuario.contrasinal === contrasinal) {
          console.log("Sesión iniciada");
          return menuPrincipal();
        } else {
          console.log("Contrasinal incorrecto.");
          return menuLogin();
        }
      });
    } else {
      console.log("O usuario non existe.");
      return menuLogin();
    }
  });
}

//************* REXISTRO **************/
function rexistrarUsuario() {
  rl.question("Introduce o novo nome de usuario: ", function (novoUsuario) {
    rl.question("Introduce novo contrasinal: ", function (novoContrasinal) {
      if (usuarios.find((user) => user.nome === novoUsuario)) {
        console.log("O usuario xa existe.");
        return menuLogin();
      }
      let novo = { nome: novoUsuario, contrasinal: novoContrasinal };
      usuarios.push(novo);
      fs.writeFileSync(__dirname + "/usuarios.json", JSON.stringify(usuarios, null, 2));
      console.log("Usuario rexistrado correctamente.");
      return menuLogin();
    });
  });
}

//***********MENÚ PRINCIPAL */
function menuPrincipal() {
  console.log("\n--- Menú principal ---");
  console.log("1. Xogar partida");
  console.log("2. Xogar contra a máquina");
  console.log("3. Amosar historial");
  if (usuario.tipo === 'administrador') {
    console.log("4. Rexistrar novo usuario");
    console.log("5. Saír");
  } else {
    console.log("4. Saír");
  }
  rl.question("Elixe unha opción: ", function (opcion) {
    switch (opcion) {
      case "1":
        xogarPartida(false);
        break;
      case "2":
        xogarPartida(true);
        break;
      case "3":
        amosarHistorial();
        break;
      case "4":
        if (usuario.tipo === "administrador") {
          rexistrarUsuario();
          break;
        }
        rl.close();
        break;
      case "5" && usuario.tipo === "administrador":
        rl.close();
        break;
    }
  });
}

//************FUNCIÓNS PARTIDA  */
function xogarPartida(maquina) {
  let puntuacion = 0;
  const cartas = ['♥', '♥', '♤', '♤', '♧', '♧'];
  let estadoCartas = new Array(6).fill(false);
  //o array de estados das cartas comeza con 6 en false, tapadas
  let paresEncontrados = 0;
  let quendaXogador = true;
  function mostrarCartas() {
    console.log("*************");
    for (let i = 0; i < 6; i++) {
      process.stdout.write(estadoCartas[i] ? `[${cartas[i]}] ` : "[X] ");
      if ((i + 1) % 3 === 0) console.log("");
    }
    console.log("*************");
  }

  function seleccionarAleatorio(estado) {
    let carta;
    do {
      carta = Math.floor(Math.random() * 6);
    } while (estado[carta]);
    return carta;
  }
  function seleccionarCarta(callback) {
    if (maquina && !quendaXogador) {
      let carta1 = seleccionarAleatorio(estadoCartas);
      estadoCartas[carta1] = true;
      console.log(`A máquina seleccionou a carta ${carta1 + 1}`);
      
      let carta2;
      do {
        carta2 = seleccionarAleatorio(estadoCartas);
      } while (carta2 === carta1);
      
      estadoCartas[carta2] = true;
      console.log(`A máquina seleccionou a carta ${carta2 + 1}`);
  
      // comprobación para a máquina
      comprobarMatch(carta1 + 1, carta2 + 1);
      
      // Continuardespois que a máquina xogue
      return continuarXogo();
    }
  
    quendaXogador = true;
    if (quendaXogador || !maquina) {
      rl.question("Selecciona unha carta (1-6): ", function (carta) {
        carta = parseInt(carta);
        if (carta < 1 || carta > 6 || estadoCartas[carta - 1]) {
          console.log("Carta inválida ou xa seleccionada.");
          return seleccionarCarta(callback);
        }
        estadoCartas[carta - 1] = true;
        callback(carta);
      });
    }
  }
  
  function comprobarMatch(carta1, carta2) {
    if (cartas[carta1 - 1] === cartas[carta2 - 1]) {
      console.log("Coinciden!");
      paresEncontrados++;
      puntuacion += 1;
      // As cartas coinciden e quedan permanentemente descubertas (mantemos estadoCartas como true)
    } else {
      console.log("Non coinciden.");
      puntuacion -= 0.1;
      estadoCartas[carta1 - 1] = false;  // Ocultar se non coinciden
      estadoCartas[carta2 - 1] = false;
      quendaXogador = !quendaXogador;  // Cambia xogador
    }
    mostrarCartas();
     // Amosa a puntuación actualizada só se non se xoga contra a máquina
  if (!maquina) {
    console.log(`Puntuación actual: ${puntuacion.toFixed(1)}`);
  }
  }

  function continuarXogo() {
    if (paresEncontrados === cartas.length / 2) {
      console.log(`Xogo rematado! Puntuación final: ${puntuacion}`);
      let partidaActual = { usuario: usuario.nome, puntos: puntuacion };
      if (!maquina) {
        puntuacions.push(partidaActual);
        fs.writeFileSync(__dirname + "/puntuacions.json", JSON.stringify(puntuacions, null, 2));
        console.log("Resultados rexistrados correctamente.");
      }
      return menuPrincipal();
    } else {
      seleccionarCarta((carta1) => {
        mostrarCartas();
        seleccionarCarta((carta2) => {
          comprobarMatch(carta1, carta2);
          continuarXogo();
        });
      });
    }
  }

  cartas.sort(() => Math.random() - 0.5);
  mostrarCartas();
  continuarXogo();
}

// ****** ** **** ** *** ***** *** **HISTORIAL
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
