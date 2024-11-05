const fs = require("fs");
const readline = require("readline");

let usuarios = JSON.parse(fs.readFileSync(__dirname + "/usuarios.json", "utf-8"));
let puntuacions = JSON.parse(fs.readFileSync(__dirname + "/puntuacions.json", "utf-8"));
const rl = readline.createInterface(process.stdin, process.stdout);

let usuario;
//----------------------------------MENÚS-----------------------------------
//*******INICIO *************/
function menuLogin() {
  console.log("Menú login:");
  console.log("1 Iniciar sesión");
  console.log("2 rexistrar usuario");
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


//********LOGIN ***************/
function iniciarSesion() {
  rl.question("Introduce o nome de usuario: ", function (nomeUsuario) {
    //función dentro do rl que opera coa palabra que s elle meteu
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

  // console.log("tipo de usuario: ");
  // console.log(usuario.tipo);


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
  // const cartas = [1, 1, 2, 2, 3, 3];  

  let puntuacion = 0;
  const cartas = ['♥', '♥', '♤', '♤', '♧', '♧'];
  let estadoCartas = new Array(6).fill(false);  
  // crea un array de 6 booleanos para o estado das cartas (seleccionadas ou non/ comezan as 6 en false)
  let paresEncontrados = 0; //para ver cando acaba a partida
  //---------
  let quendaXogador = true;

  //--------ENSINA CARTAS
  function mostrarCartas() {
    console.log("*************");
    //bucle pra ensinar as cartas
    for (let i = 0; i < 6; i++) {
      process.stdout.write(estadoCartas[i] ? `[${cartas[i]}] ` : "[X] ");
      //process.stdout.write() función que permite escribir na consola, 
      //non fai como o console.log que marca un salto de liña en cada un, este reproduce tal cal


      // estadocartas[i] ---booleano que indica se a carta está seleccionada ou non, se está seleccionada vese a figura, se non o X
      if ((i + 1) % 3 === 0) console.log("");  
      // Formato 3x2. cada vez que amosa 3 cartas fai un salto de liña, como o for sae 6 veces amosa 6 cartas(dúas filas)
    }
    console.log("*************");
  }
  //pra que a máquina seleccione
  function seleccionarAleatorio(estado) {
    let carta;
    do {
      carta = Math.floor(Math.random() * 6);
    } while (estado[carta]);
    return carta;
  }
  //--------------MARCA CARTA
  function seleccionarCarta(callback) {
    //márcase o callback en seleccionar carta
    if (maquina && !quendaXogador) {
      let carta1 = seleccionarAleatorio(estadoCartas);
      estadoCartas[carta1] = true; //marca seleccionada ese número pasa a true
      console.log(`A máquina seleccionou a carta ${carta1 + 1}`);
      let carta2;
      do {
        carta2 = seleccionarAleatorio(estadoCartas);
      } while (carta2 === carta1);
//se a segunda non é a mesma ca primeira marca a segunda
      estadoCartas[carta2] = true;
      console.log(`A máquina seleccionou a carta ${carta2 + 1}`);

      callback(carta1 + 1, carta2 + 1); //mándase o callback
    }
    estadoCartas = [false, false]
    quendaXogador = true;
    if (quendaXogador || !maquina) {
      rl.question("Selecciona unha carta (1-6): ", function (carta) {
        carta = parseInt(carta);
        if (carta < 1 || carta > 6 || estadoCartas[carta - 1]) {
          //parsea e comproba que non se sae do rango 1-6
          console.log("Carta inválida ou xa seleccionada.");
          return seleccionarCarta(callback);  // Volver escoller
        }
        estadoCartas[carta - 1] = true;  // Marcar carta seleccionada
        callback(carta);//mándase outro callback
        //o callback  é o que se executa despois de seleccionar a carta na última función onde se declarou(neste caso seleccionarcarta)

      });
    } else {

    }
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
      estadoCartas[carta1 - 1] = false;  // Desmarca a carta no array
      estadoCartas[carta2 - 1] = false;
      quendaXogador = !quendaXogador;
    }
    //volver de novo a mostrar o cadro de cartas (coas parellas xa feitas)
    mostrarCartas();
  }

  function continuarXogo() {
    if (paresEncontrados === cartas.length / 2) {
      console.log(`Xogo rematado! Puntuación final: ${puntuacion}`);
      let partidaActual = { usuario: usuario.nome, puntos: puntuacion };
      if (!maquina) {
        puntuacions.push(partidaActual);
        // gardar partida no json usuario:... - puntos:....
        fs.writeFileSync(__dirname + "/puntuacions.json", JSON.stringify(puntuacions, null, 2));
        console.log("Resultados rexistrados correctamente.");
      } else {
        console.log(`Puntuación final: ${puntuacion}`)
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

  // barallar
  cartas.sort(() => Math.random() - 0.5);
  //o mathrandom dá valor entre 0 e 1, ao restar 0.5 querá un valor entre -0.5 ou +0.5.
  //o sort ordena o array poñendo antes os negativos, logo positivos e o 0 sen cambios
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

  menuPrincipal();
}


menuLogin();