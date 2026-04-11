<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado</title>
    <link rel="stylesheet" href="style.css?v=1.4">
</head>

<body>
    <h1 class="main-title" style="margin-top: 40px;">Resultado de la Partida</h1>

    <div class="pantalla-juego-estado">
        <div class="formularios-wrapper" style="align-items: stretch; max-width: 900px;">

            <!-- Panel Rendimiento Jugador 1 -->
            <div id="resultadoU1" class="registro-container">
                <!-- Se inyectará por JS -->
            </div>

            <!-- Panel Central: Info Global -->
            <div class="registro-container" style="flex: 1.5 1 0;">
                <h2 class="subtitulo-juego" style="margin-bottom:20px;">Resumen</h2>
                <div id="datosPartida" style="font-size:18px; color:#475569; margin-bottom: 25px; line-height:1.6;">
                    <!-- Se inyectará por JS -->
                </div>

                <!-- Botoneras para navegar -->
                <a href="configuracion_partida.php" class="btn-submit"
                    style="text-decoration:none; display:inline-block; margin-bottom: 15px;">Jugar Revancha</a>
                <a href="#" id="btnCerrarSesion" class="enlace-volver">Salir y Cerrar Sesión</a>
            </div>

            <!-- Panel Rendimiento Jugador 2 -->
            <div id="resultadoU2" class="registro-container">
                <!-- Se inyectará por JS -->
            </div>

        </div>
    </div>

    <!-- Script enlazado externamente para manejo de reportes y calculos porcentuales -->
    <script src="script_resultado.js"></script>
</body>

</html>