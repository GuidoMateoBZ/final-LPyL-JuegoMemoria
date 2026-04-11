<?php
session_start();
if (!isset($_SESSION['usuario1']) || !isset($_SESSION['usuario2'])) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Memoria</title>
    <link rel="stylesheet" href="style.css?v=1.4">
    <script src="script.js" defer></script>
</head>

<body>
    <h1 class="main-title">Juego Memoria</h1>

    <!-- PANTALLA DE SORTEO -->
    <div id="pantalla-sorteo" class="pantalla-juego-estado">
        <div class="formularios-wrapper">
            <div class="registro-container" style="max-width: 500px;">
                <h2 class="subtitulo-juego">Sorteo de Turno</h2>
                <p id="mensaje-sorteo" class="jugador-msj">
                    Presioná el botón inferior para que el sistema decida al azar quién hace el primer movimiento de la
                    partida.
                </p>
                <div style="margin-top:20px;">
                    <button id="btnSortear" class="btn-submit" onclick="ejecutarSorteo()">Sortear Jugador
                        Inicial</button>
                    <button id="btnJugar" class="btn-submit btn-comenzar-juego pantalla-oculta"
                        onclick="iniciarJuego()">Jugar Ahora</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PANTALLA DEL TABLERO (Oculta hasta pasar el sorteo) -->
    <div id="pantalla-juego" class="pantalla-juego-estado pantalla-oculta">

        <div class="formularios-wrapper">

            <!-- Panel Izquierdo: Jugador 1 -->
            <div id="panel-j1" class="registro-container panel-jugador">
                <h2 class="subtitulo-juego">Jugador 1</h2>
                <h3 class="nombre-participante"><?php echo $_SESSION['usuario1']['nombre_usuario']; ?></h3>

                <div class="modulo-estadisticas">
                    <p>Pares hallados: <br><span id="pares1" class="dato-pares">0</span></p>
                    <p>Intentos restantes: <br><span id="intentos1" class="dato-intentos">0</span></p>
                </div>
                <div class="btn-abandono">
                    <button id="btnAbandono1" class="btn-submit" style="margin-top: 30px;"
                        onclick="abandonarPartida(1)">Abandonar Partida</button>
                </div>
            </div>

            <!-- Panel Central: Tablero y Tiempo -->
            <div class="registro-container panel-tablero">
                <h2 class="subtitulo-juego">Tablero de Juego</h2>
                <p class="tiempo-texto">Tiempo restante: <span id="tiempo" class="tiempo-valor">--</span></p>

                <!-- Acá JavaScript inyectará las cartas -->

                <div class="tablero" id="tablero"></div>
            </div>

            <!-- Panel Derecho: Jugador 2 -->
            <div id="panel-j2" class="registro-container panel-jugador">
                <h2 class="subtitulo-juego">Jugador 2</h2>
                <h3 class="nombre-participante"><?php echo $_SESSION['usuario2']['nombre_usuario']; ?></h3>

                <div class="modulo-estadisticas">
                    <p>Pares hallados: <br><span id="pares2" class="dato-pares">0</span></p>
                    <p>Intentos restantes: <br><span id="intentos2" class="dato-intentos">0</span></p>
                </div>
                <div class="btn-abandono">
                    <button id="btnAbandono2" class="btn-submit" style="margin-top: 30px;"
                        onclick="abandonarPartida(2)">Abandonar Partida</button>
                </div>
            </div>

        </div>

    </div>

    <!-- Transferimos los nombres a JS de manera limpia -->
    <script>
        window.nombreJugador1 = "<?php echo $_SESSION['usuario1']['nombre_usuario']; ?>";
        window.nombreJugador2 = "<?php echo $_SESSION['usuario2']['nombre_usuario']; ?>";
    </script>
</body>

</html>