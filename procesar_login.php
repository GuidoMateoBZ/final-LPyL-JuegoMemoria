<?php
include_once("Usuario.class.php");

session_start();
header('Content-Type: application/json');

$nombre = $_POST['nombre'];
$contrasenia = $_POST['contrasenia'];
$numUsuario = $_POST['numUsuario'];

// Verificamos si el otro jugador que ya está en sesión tiene el mismo nombre
$otroNum = ($numUsuario == 1) ? 2 : 1;
if (isset($_SESSION['usuario' . $otroNum]) && $_SESSION['usuario' . $otroNum]['nombre_usuario'] === $nombre) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Este usuario ya inició sesión como el Jugador ' . $otroNum . '. Elija otro perfil.'
    ]);
    exit;
}

// Instanciamos el objeto con los datos validados
$loginUsuario = new Usuario(null, $nombre, $contrasenia);

// Guardamos el usuario en la base de datos y devolvemos la respuesta al JavaScript
$respuesta = $loginUsuario->iniciarSesion();

if ($respuesta['exito']) {
    $_SESSION['usuario' . $numUsuario] = [
        'id_usuario' => $loginUsuario->getIdUsuario(),
        'nombre_usuario' => $nombre
    ];
}

echo json_encode($respuesta);
?>