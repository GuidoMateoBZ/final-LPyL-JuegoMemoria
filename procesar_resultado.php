<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario1']) || !isset($_SESSION['usuario2'])) {
    echo json_encode(["exito" => false, "mensaje" => "No hay sesión activa."]);
    exit;
}

require_once 'Partida.class.php';
require_once 'GestorPartida.class.php';

// Leemos los datos JSON
$inputJSON = isset($_POST['datos']) ? $_POST['datos'] : '';
$datos = json_decode($inputJSON, true);

if (!$datos) {
    echo json_encode(["exito" => false, "mensaje" => "El formato de datos enviado es inválido o nulo."]);
    exit;
}

$p_data = $datos['tabla_partida'];
$up_data = $datos['tabla_usuario_partida'];

// Convertimos los segundos netos al formato SQL TIME (HH:MM:SS)
$segundos = intval($p_data['tiempo_jugado']);
$hh = sprintf('%02d', intval($segundos / 3600));
$mm = sprintf('%02d', intval(($segundos % 3600) / 60));
$ss = sprintf('%02d', $segundos % 60);
$tiempoFormato = "$hh:$mm:$ss";

// Iniciamos la única conexión para la transacción
$gestor = new GestorPartida();
$con = $gestor->getCon();

// ABRIMOS LA TRANSACCION SQL
$con->begin_transaction();

// Guardamos la Partida base inyectando la conexión abierta
$partida = new Partida();
$partida->setDificultad($p_data['dificultad']);
$partida->setTiempoJugado($tiempoFormato);

$resultadoPartida = $partida->guardarPartida($con);

if (!$resultadoPartida['exito']) {
    $con->rollback(); // Revertimos todo
    echo json_encode(["exito" => false, "mensaje" => "Fallo al registrar tabla Partida: " . $resultadoPartida['mensaje']]);
    exit;
}

// Rescatamos el ID autoincremental que devolvió el método
$id_partida = $resultadoPartida['id_partida'];

// Guardamos la hoja de datos relacional para el JUGADOR 1
$datosJugador1 = [
    'id_usuario' => $_SESSION['usuario1']['id_usuario'],
    'id_partida' => $id_partida,
    'puntaje' => $up_data['jugador1']['puntaje'],
    'pares_descubiertos' => $up_data['jugador1']['pares_descubiertos'],
    'intentos' => $up_data['jugador1']['intentos'],
    'id_resultado' => $up_data['jugador1']['id_resultado']
];
$respU1 = $gestor->guardarUsuarioPartida($datosJugador1);

// Guardamos la hoja de datos relacional para el JUGADOR 2
$datosJugador2 = [
    'id_usuario' => $_SESSION['usuario2']['id_usuario'],
    'id_partida' => $id_partida,
    'puntaje' => $up_data['jugador2']['puntaje'],
    'pares_descubiertos' => $up_data['jugador2']['pares_descubiertos'],
    'intentos' => $up_data['jugador2']['intentos'],
    'id_resultado' => $up_data['jugador2']['id_resultado']
];
$respU2 = $gestor->guardarUsuarioPartida($datosJugador2);


if ($respU1['exito'] && $respU2['exito']) {
    $con->commit(); // Se confirma porque nada falló
    echo json_encode(["exito" => true, "mensaje" => "La partida y sus estadísticas se sincronizaron con SQL exitosamente en bloque."]);
} else {
    $con->rollback(); // Se anula la partida creada al principio
    echo json_encode(["exito" => false, "mensaje" => "Error guardando tabla cruzada de usuarios. Volviendo atrás..."]);
}
?>