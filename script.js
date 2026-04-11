var usuario1;
var usuario2;


var partida = JSON.parse(sessionStorage.getItem("partida"));
var dificultadTotal = parseInt(partida.dificultad[0]);
var intentosTotales = parseInt(partida.dificultad[1]);
var paresTotales = dificultadTotal / 2;
var tiempo = parseInt(partida.tiempo);
var temporizadorId;

var finJuego = false;
var pares1 = 0;
var pares2 = 0;
var intentos1 = intentosTotales;
var intentos2 = intentosTotales;
var turno;
var cartasVolteadas = [];

const aviones_imgs = [
    "aviones/01_Boeing_737_MAX.jpg", "aviones/02_Boeing_747_400.jpg", "aviones/03_Boeing_777_300ER.jpg",
    "aviones/04_Boeing_787_Dreamliner.jpg", "aviones/05_Boeing_737_800.jpg", "aviones/06_Boeing_767_300ER.jpg",
    "aviones/07_Airbus_A220_300.jpg", "aviones/08_Airbus_A320neo.jpg", "aviones/09_Airbus_A321XLR.jpg",
    "aviones/10_Airbus_A330_300.jpg", "aviones/11_Airbus_A350_XWB.jpg", "aviones/12_Airbus_A380.jpg",
    "aviones/13_Embraer_E175.jpg", "aviones/14_Embraer_E195_E2.jpg", "aviones/15_Bombardier_CRJ900.jpg",
    "aviones/16_ATR_72_600.jpg"
];

const futbol_imgs = [
    "futbol_argentino/01_River_Plate.jpg", "futbol_argentino/02_Boca_Juniors.jpg", "futbol_argentino/03_Racing_Club.jpg",
    "futbol_argentino/04_Independiente.jpg", "futbol_argentino/05_San_Lorenzo.jpg", "futbol_argentino/06_Huracan.jpg",
    "futbol_argentino/07_Estudiantes.jpg", "futbol_argentino/08_Velez_Sarsfield.jpg", "futbol_argentino/09_Lanus.jpg",
    "futbol_argentino/10_Belgrano.jpg", "futbol_argentino/11_Talleres.jpg", "futbol_argentino/12_Newells_Old_Boys.jpg",
    "futbol_argentino/13_Rosario_Central.jpg", "futbol_argentino/14_Godoy_Cruz.jpg", "futbol_argentino/15_Atletico_Tucuman.jpg",
    "futbol_argentino/16_Gimnasia_La_Plata.png"
];

function crearMazo() {
    var mazo = [];
    switch (partida.tematica) {
        case "numeros":
            for (let i = 1; i <= paresTotales; i++) {
                mazo.push({ valor: i, img: null });
                mazo.push({ valor: i, img: null });
            }
            break;
        case "futbol":
            // Mezclamos para que no sean siempre los primeros si la dificultad es baja
            var futbol = [...futbol_imgs].sort(() => Math.random() - 0.5);
            for (let i = 1; i <= paresTotales; i++) {
                mazo.push({ valor: i, img: futbol[i - 1] });
                mazo.push({ valor: i, img: futbol[i - 1] });
            }
            break;

        case "aviones":
            var aviones = [...aviones_imgs].sort(() => Math.random() - 0.5);
            for (let i = 1; i <= paresTotales; i++) {
                mazo.push({ valor: i, img: aviones[i - 1] });
                mazo.push({ valor: i, img: aviones[i - 1] });
            }
            break;
    }

    // Barajamos el mazo
    mazo.sort(() => Math.random() - 0.5);
    return mazo;
}

function colocarCartas() {
    var mazo = crearMazo();
    var contenedor = document.getElementById("tablero");
    contenedor.innerHTML = "";
    for (let i = 0; i < mazo.length; i++) {
        var carta = document.createElement("div");
        carta.classList.add("carta");

        carta.dataset.valor = mazo[i].valor;
        if (mazo[i].img) {
            carta.dataset.img = mazo[i].img;
            carta.style.backgroundImage = `url('${mazo[i].img}')`;
        } else {
            // Si es número, lo imprimimos dentro del div
            carta.textContent = mazo[i].valor;
        }

        carta.addEventListener("click", voltearCarta);
        contenedor.appendChild(carta);
    }
}

function voltearCarta(e) {
    var carta = e.target;
    //Evita que se pueda hacer click en más de 2 cartas
    if (cartasVolteadas.length >= 2 || carta.classList.contains("volteada")) {
        return;
    }

    carta.classList.add("volteada");
    carta.removeEventListener("click", voltearCarta);

    cartasVolteadas.push(carta);

    // Se comprueba si es la segunda carta que se voltea
    if (cartasVolteadas.length === 2) {
        //Se resta un intento al jugador que tiene el turno
        if (turno == 1) {
            intentos1--;
            document.getElementById("intentos1").textContent = intentos1;
        } else {
            intentos2--;
            document.getElementById("intentos2").textContent = intentos2;
        }

        // Se comprueba si son iguales
        if (cartasVolteadas[0].dataset.valor === cartasVolteadas[1].dataset.valor) {
            cartasVolteadas[0].classList.add("acertada");
            cartasVolteadas[1].classList.add("acertada");
            cartasVolteadas = [];
            //Sumar par al jugador que tiene el turno
            if (turno == 1) {
                pares1++;
                document.getElementById("pares1").textContent = pares1;
            } else {
                pares2++;
                document.getElementById("pares2").textContent = pares2;
            }

        } else {
            // Retraso de 1 segundo para que el jugador pueda ver la carta que abrió antes de esconderse automáticamente
            setTimeout(function () {
                cartasVolteadas[0].classList.remove("volteada");
                cartasVolteadas[1].classList.remove("volteada");
                cartasVolteadas[0].addEventListener("click", voltearCarta);
                cartasVolteadas[1].addEventListener("click", voltearCarta);

                cartasVolteadas = [];
                cambioTurno();
            }, 1000);
        }
    }
    finalizarJuego(); //Se comprueba si se ha acabado el juego
}

function cambioTurno() {
    if (turno == 1) {
        turno = 2;
    } else {
        turno = 1;
    }
    actualizarVisualTurno();
}

function actualizarVisualTurno() {
    var panel1 = document.getElementById("panel-j1");
    var panel2 = document.getElementById("panel-j2");

    // Le ponemos un borde verde al que está jugando, transparente al otro
    if (turno === 1) {
        panel1.style.borderColor = "#22c55e";
        panel2.style.borderColor = "transparent";
    } else {
        panel2.style.borderColor = "#22c55e";
        panel1.style.borderColor = "transparent";
    }
}

function ejecutarSorteo() {
    //Se sortea el que inicia
    turno = Math.floor(Math.random() * 2) + 1;

    var nombreGanador = turno === 1 ? window.nombreJugador1 : window.nombreJugador2;

    // Ocultamos botón sortear y mostramos resultado
    document.getElementById("btnSortear").classList.add("pantalla-oculta");

    var msj = document.getElementById("mensaje-sorteo");
    msj.innerHTML = "¡El sorteo ha decidido que comienza <strong>" + nombreGanador + "</strong>!";

    // Mostramos botón para arrancar
    document.getElementById("btnJugar").classList.remove("pantalla-oculta");
}

function finalizarJuego() {
    if ((pares1 + pares2) === paresTotales) {
        finJuego = true;
    }
    if (intentos1 === 0 || intentos2 === 0) {
        finJuego = true;
    }
}

function abandonarPartida(jugador) {
    finalizarJuego();
}

function iniciarJuego() {
    console.log("Variables: ",
        "\npartida: ", partida
        , "\ndificultadTotal: ", dificultadTotal
        , "\nintentosTotales: ", intentosTotales
        , "\nparesTotales: ", paresTotales
        , "\ntiempo: ", tiempo
        , "\ntemporizadorId: ", temporizadorId
        , "\nfinJuego: ", finJuego
        , "\npares1: ", pares1
        , "\npares2: ", pares2
        , "\nintentos1: ", intentos1
        , "\nintentos2: ", intentos2
        , "\nturno: ", turno
        , "\ncartasVolteadas: ", cartasVolteadas);

    // Ocultar pantalla de sorteo y mostrar juego
    document.getElementById("pantalla-sorteo").classList.add("pantalla-oculta");
    document.getElementById("pantalla-juego").classList.remove("pantalla-oculta");

    document.getElementById("intentos1").textContent = intentos1;
    document.getElementById("intentos2").textContent = intentos2;

    actualizarVisualTurno();

    // Calculamos las columnas
    var columnasBase = paresTotales === 16 ? 6 : 4;
    document.getElementById("tablero").style.gridTemplateColumns = `repeat(${columnasBase}, 1fr)`;

    colocarCartas();

    // Autofoco
    document.getElementById("pantalla-juego").scrollIntoView({ behavior: 'smooth', block: 'center' });

    // Si la partida no tiene tiempo limite, tiempo va a ser 0.
    if (tiempo > 0) {
        temporizadorId = setInterval(function () {
            tiempo--;
            document.getElementById("tiempo").textContent = tiempo;

            // Si llega a cero, apagar el reloj y forzar el fin del juego
            if (tiempo === 0) {
                clearInterval(temporizadorId);
                finJuego = true;
            }
        }, 1000);
    }
}
