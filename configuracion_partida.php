<?php
session_start();
if (!isset($_SESSION['usuario1']) || !isset($_SESSION['usuario2'])) {
    header("Location: index.php");
    exit;
}
include_once("GestorPartida.class.php");

$gestorPartida = new GestorPartida();
$u1_id = $_SESSION['usuario1']['id_usuario'];
$u2_id = $_SESSION['usuario2']['id_usuario'];
$u1_nombre = $_SESSION['usuario1']['nombre_usuario'];
$u2_nombre = $_SESSION['usuario2']['nombre_usuario'];
include_once("Partida.class.php");

$mensaje_general = "";
$mensaje_u1 = "Divertite y jugá, esta es tu primera partida.";
$mensaje_u2 = "Divertite y jugá, esta es tu primera partida.";

$resJuntos = $gestorPartida->obtenerUltimaPartidaJuntos($u1_id, $u2_id);

if ($resJuntos['exito'] && !empty($resJuntos['datos'])) {
    // Ya jugaron juntos antes
    $datos = $resJuntos['datos'];
    $partidaObj = new Partida($datos['id_partida'], $datos['fecha'], $datos['dificultad'], $datos['tiempo_jugado']);

    $fecha_formateada = date("d/m/Y", strtotime($partidaObj->getFecha()));
    $dificultad = $partidaObj->getDificultad();
    $tiempo = $partidaObj->getTiempoJugado();
    $pts1 = $datos['puntaje1'];
    $pts2 = $datos['puntaje2'];

    $mensaje_general = "La última vez que jugaron juntos fue el $fecha_formateada (Dificultad $dificultad).";
    $mensaje_u1 = "En ese combate obtuviste $pts1 puntos.";
    $mensaje_u2 = "En ese combate obtuviste $pts2 puntos.";
} else {
    // Nunca jugaron juntos, revisamos individualmente
    $resU1 = $gestorPartida->obtenerUltimaPartidaUsuarioConOponente($u1_id);
    if ($resU1['exito'] && !empty($resU1['datos'])) {
        $datosU1 = $resU1['datos'];
        $partidaObj = new Partida($datosU1['id_partida'], $datosU1['fecha'], $datosU1['dificultad'], $datosU1['tiempo_jugado']);

        $fecha_formateada = date("d/m/Y", strtotime($partidaObj->getFecha()));
        $oponente = $datosU1['oponente'];
        $dificultad = $partidaObj->getDificultad();

        $mensaje_u1 = "Tu última partida (contra $oponente el $fecha_formateada) fue de dificultad $dificultad. ¡Mucha suerte hoy!";
    }

    $resU2 = $gestorPartida->obtenerUltimaPartidaUsuarioConOponente($u2_id);
    if ($resU2['exito'] && !empty($resU2['datos'])) {
        $datosU2 = $resU2['datos'];
        $partidaObj2 = new Partida($datosU2['id_partida'], $datosU2['fecha'], $datosU2['dificultad'], $datosU2['tiempo_jugado']);

        $fecha_formateada2 = date("d/m/Y", strtotime($partidaObj2->getFecha()));
        $oponente2 = $datosU2['oponente'];
        $dificultad2 = $partidaObj2->getDificultad();

        $mensaje_u2 = "Tu última partida fue el $fecha_formateada2 contra $oponente2 (Dificultad $dificultad2). ¡A ganarle a $u1_nombre!";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Partida</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="main-title">Configuración de Partida</h1>
    <?php if ($mensaje_general !== ""): ?>
        <!-- Mensaje de bienvenida conjunto -->
        <div class="mensaje-general">
            <?php echo $mensaje_general; ?>
        </div>
    <?php endif; ?>

    <div class="formularios-wrapper" style="margin-bottom: 30px;">
        <!-- Información Jugador 1 -->
        <div class="registro-container">
            <h1 style="margin-bottom: 15px;">Jugador 1</h1>
            <p style="font-size: 20px; font-weight: bold; color: #0f172a; margin-bottom: 15px;">
                <?php echo $_SESSION['usuario1']['nombre_usuario']; ?>
            </p>
            <p class="jugador-msj">
                <?php echo $mensaje_u1; ?>
            </p>
        </div>

        <!-- Formulario para configurar la partida -->
        <div class="registro-container">
            <h1 style="margin-bottom: 15px;">Nueva Partida</h1>
            <form id="formConfiguracion">
                <div class="form-group">
                    <label for="dificultad">Selecciona una Dificultad</label>
                    <select name="dificultad" id="dificultad" required>
                        <option value="8,20">Baja (4 pares - 20 intentos)</option>
                        <option value="16,40">Media (8 pares - 40 intentos)</option>
                        <option value="32,64">Alta (16 pares - 64 intentos)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tematica">Selecciona una Temática</label>
                    <select name="tematica" id="tematica" required>
                        <option value="numeros">Números</option>
                        <option value="futbol">Fútbol</option>
                        <option value="aviones">Aviones</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tiempo">Selecciona un Tiempo</label>
                    <select name="tiempo" id="tiempo" required>
                        <option value="240">4 minutos</option>
                        <option value="900">15 minutos</option>
                        <option value="1200">20 minutos</option>
                        <option value="0">Sin límite de tiempo</option>
                    </select>
                </div>
                <input type="submit" value="Jugar" class="btn-submit">
            </form>
        </div>

        <!-- Información Jugador 2 -->
        <div class="registro-container">
            <h1 style="margin-bottom: 15px;">Jugador 2</h1>
            <p style="font-size: 20px; font-weight: bold; color: #0f172a; margin-bottom: 15px;">
                <?php echo $_SESSION['usuario2']['nombre_usuario']; ?>
            </p>
            <p class="jugador-msj">
                <?php echo $mensaje_u2; ?>
            </p>
        </div>
    </div>

    <a href="#" id="btnSalir" class="enlace-volver-inverso">Cerrar Sesión y Salir</a>

    <script>
        document.getElementById('formConfiguracion').addEventListener('submit', function (e) {
            e.preventDefault();
            const dificultad = document.getElementById('dificultad').value;
            const tematica = document.getElementById('tematica').value;
            const tiempo = document.getElementById('tiempo').value;
            sessionStorage.setItem('partida', JSON.stringify({
                dificultad: dificultad.split(','),
                tematica: tematica,
                tiempo: tiempo
            }));
            window.location.href = 'juego.php';
        });

        document.getElementById('btnSalir').addEventListener('click', function (e) {
            e.preventDefault();
            sessionStorage.clear();
            window.location.href = 'logout.php';
        });
    </script>
</body>

</html>