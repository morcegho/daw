const fs = require("fs");
const readline = require("readline");

let usuarios = JSON.parse(fs.readFileSync(__dirname + "/usuarios.json", "utf-8"));
let puntuacions = JSON.parse(fs.readFileSync(__dirname + "/puntuacions.json", "utf-8"));
const rl = readline.createInterface(process.stdin, process.stdout);

let usuario;  // Variable para almacenar el usuario actualmente conectado
//----------------------------------MENÚS-------------------------------------------
//*******INICIO */
function menuLogin() {
  console.log("Menú login:");
  console.log("1 Iniciar sesión");
  console.log("2 Rexistrar novo usuario");
  console.log("3 Saír");
  rl.question("Elixe unha opción: ", function (opcion) {
    switch (opcion) {
      case "1":
        iniciarSesion();
        break;
      case "2":
        rexistrarUsuario();
        break;
      case "3":
        rl.close();
        break;
    }
  });
}
//********LOGIN */
function iniciarSesion() {
  rl.question("Introduce o nome de usuario: ", 
    function (nomeUsuario) { usuario = usuarios.find((user) => user.nome === nomeUsuario);
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
//*************REXISTRO */
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
  console.log("Menú principal:");
  console.log("1 Competir");
  // console.log("2 Modo competitivo");
  // console.log("3 Amosar o historial");
  console.log("2 Saír");
  rl.question("Elixe unha opción: ", function (opcion) {
    switch (opcion) {
      case "1":
        xogarPartida();
        break;
      case "2":
        modoCompetitivo();
        break;
      case "3":
        amosarHistorial();
        break;
      case "4":
        rl.close();
        break;
    }
  });
}

let competitivo = false;

//***********MODO COMPETITIVO  */

function menuPrincipal() {
  console.log("Modo xogo:");
  console.log("1 Nova partida");
  console.log("2 Modo competitivo");
  console.log("3 Amosar o historial");
  console.log("4 Saír");
  rl.question("Elixe unha opción: ", function (opcion) {
    switch (opcion) {
      case "1":
        xogarPartida();
        break;
      case "2":
        modoCompetitivo();
        break;
      case "3":
        amosarHistorial();
        break;
      case "4":
        rl.close();
        break;
    }
  });
}

//--------ENSINA CARTAS
function modoCompetitivo() {
  competitivo = true;
  console.log("****COMPETITIVO*********");
  rl.close()
}



//************FUNCIÓNS PARTIDA  */

function xogarPartida(puntuacion = 0) {
  // const cartas = [1, 1, 2, 2, 3, 3];  
  const cartas = ['♥', '♥', '♤', '♤', '♧', '♧'];

  let estadoCartas = new Array(6).fill(false);  // Estado das cartas (seleccionadas ou non)
  let paresEncontrados = 0;


  //--------ENSINA CARTAS
  function mostrarCartas() {
    console.log("*************");
    for (let i = 0; i < 6; i++) {
      process.stdout.write(estadoCartas[i] ? `[${cartas[i]}] ` : "[X] ");
      if ((i + 1) % 3 === 0) console.log("");  // Formato 3x2
    }
    console.log("*************");
  }

  //--------------MARCA CARTA
  function seleccionarCarta(callback) {
    rl.question("Selecciona unha carta (1-6): ", function (carta) {
      carta = parseInt(carta);
      if (carta < 1 || carta > 6 || estadoCartas[carta - 1]) {
        console.log("Carta inválida ou xa seleccionada.");
        return seleccionarCarta(callback);  // Volver escoller
      }
      estadoCartas[carta - 1] = true;  // Marcar carta seleccionada
      callback(carta);
    });
  }
  //---- COMPROBA COINCIDENCIA
  function comprobarMatch(carta1, carta2) {
    if (cartas[carta1 - 1] === cartas[carta2 - 1]) {
      console.log("Coinciden!");
      paresEncontrados++;
      puntuacion += 1;
    } else {
      console.log("Non coinciden.");
      puntuacion -= 0.1;
      estadoCartas[carta1 - 1] = false;  // Desmarcar
      estadoCartas[carta2 - 1] = false;
    }
    //volver de novo a mostrar o cadro de cartas (coas parellas xa feitas)
    mostrarCartas();
  }

  function continuarXogo() {
    if (paresEncontrados === cartas.length / 2) {
      console.log(`Xogo rematado! Puntuación final: ${puntuacion}`);
      let partidaActual = { usuario: usuario.nome, puntos: puntuacion };
      puntuacions.push(partidaActual);
      // gardar partida no json usuario - puntos
      fs.writeFileSync(__dirname + "/puntuacions.json", JSON.stringify(puntuacions, null, 2));
      console.log("Resultados rexistrados correctamente.");
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

  // barallar
  cartas.sort(() => Math.random() - 0.5);

  mostrarCartas();
  continuarXogo();
}

//  ****** ** **** ** *** ***** *** **HISTORIAL
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

  menuPrincipal();  // Volver ao menú principal despois de mostrar
}






// -----------LÓXICA DA MÁQUINA

//--------------MARCA CARTA
function seleccionarCartaMáquina() {
  // rl.question("Selecciona unha carta (1-6): ", function (carta) {
  //   carta = parseInt(carta);
  //   if (carta < 1 || carta > 6 || estadoCartas[carta - 1]) {
  //     console.log("Carta inválida ou xa seleccionada.");
  //     return seleccionarCarta(callback);  // Volver escoller
  //   }
  //   estadoCartas[carta - 1] = true;  // Marcar carta seleccionada
  //   callback(carta);
  // });
}

menuLogin();
