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
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>

<body>
    <div class="juego-container">
        <h1>Juego Memoria</h1>
        <p>Tiempo restante: <span id="tiempo">0</span>s</p>
        <div class="info-partida">
            <p>Jugador 1: <?php echo $_SESSION['usuario1']['nombre_usuario']; ?></p>
            <p>Pares descubiertos: <span id="pares1">0</span></p>
            <p>Intentos: <span id="intentos1">0</span></p>
        </div>
        <div class="tablero" id="tablero">
            <!-- Las cartas se insertarán aquí mediante JavaScript -->
        </div>
        <div class="info-partida">
            <p>Jugador 2:
                <?php echo $_SESSION['usuario2']['nombre_usuario']; ?>
            </p>
            <p>Pares descubiertos: <span id="pares2">0</span></p>
            <p>Intentos: <span id="intentos2">0</span></p>
        </div>
        <button id="btnSalir">Salir</button>
    </div>
</body>

</html>