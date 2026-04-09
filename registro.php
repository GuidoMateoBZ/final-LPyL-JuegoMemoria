<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Memoria</title>
</head>

<body>
    <h1>Registro</h1>
    <p>Acá iría el registro</p>
    <form action="" method="post">
        <label for="nombre">Nombre de usuario</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="contrasenia">Contraseña</label>
        <input type="password" id="contrasenia" name="contrasenia" required>
        <label for="edad">Edad</label>
        <input type="number" id="edad" name="edad" required>
        <select name="pais" id="pais" required>
            <option value="none">Seleccione un país</option>
            <option value="001">Argentina</option>
            <option value="002">Brasil</option>
            <option value="003">Chile</option>
            <option value="004">Colombia</option>
            <option value="005">Peru</option>
        </select>
        <input type="submit" value="Registrarse" name="btnRegistrarse">
    </form>
    <?php
    if (isset($_POST['btnRegistrarse'])) {
        $nombre = $_POST['nombre'];
        $contrasenia = $_POST['contrasenia'];
        $edad = $_POST['edad'];
        $id_pais = $_POST['pais'];
        $con = new mysqli("localhost", "root", "", "juego_memoria") or die("Error al conectar a la base de datos");
        $query = "INSERT INTO usuario (nombre, contrasenia, edad, id_pais) VALUES ('$nombre', '$contrasenia', '$edad', '$id_pais')";
        $con->query($query) or die("Error al insertar el jugador");
        $con->close();
        echo "Jugador insertado correctamente";
    }
    ?>
</body>

</html>