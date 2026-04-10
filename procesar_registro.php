<?php
include_once("Usuario.class.php");

$nombre = $_POST['nombre'];
$contrasenia_plana = $_POST['contrasenia'];
$fecha_nacimiento = $_POST['fecha_nacimiento'];
$id_pais = $_POST['id_pais'];

// Validación de edad
$fecha_nac_obj = new DateTime($fecha_nacimiento);
$hoy = new DateTime();
$edad = $hoy->diff($fecha_nac_obj)->y;

if ($edad <= 12) {
    echo "Lo sentimos, debes ser mayor de 12 años para registrarte.";
    exit;
}

// Validamos el país
if ($id_pais === "none") {
    echo "Por favor, selecciona un país válido.";
    exit;
}

// Hasheamos la contraseña por seguridad
$contrasenia_hash = password_hash($contrasenia_plana, PASSWORD_DEFAULT);

// Instanciamos el objeto con los datos validados
$nuevoUsuario = new Usuario(null, $nombre, $contrasenia_hash, $id_pais, $fecha_nacimiento);

// Guardamos el usuario en la base de datos y devolvemos la respuesta al JavaScript
$respuesta = $nuevoUsuario->registrar();

echo $respuesta;
?>