const fs = require('fs');
const readline = require('readline');

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

function mostrarMenu() {
  console.log("1. Registrarse");
  console.log("2. Iniciar sesión");
  console.log("3. Salir");
  rl.question("Elige una opción: ", function (opcion) {
    switch (opcion) {
      case '1':
        registrarse();
        break;
      case '2':
        iniciarSesion();
        break;
      case '3':
        rl.close();
        break;
      default:
        console.log("Opción no válida");
        mostrarMenu();
        break;
    }
  });
}
mostrarMenu()