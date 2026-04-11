<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Juego Memoria</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="registro-container" style="width: 33%;">
        <h1>Registro</h1>
        <form id="formRegistro">
            <div class="form-group">
                <label for="nombre">Nombre de usuario</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="contrasenia">Contraseña</label>
                <input type="password" id="contrasenia" name="contrasenia" required>
            </div>

            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>

            <div class="form-group">
                <label for="id_pais">País</label>
                <select name="id_pais" id="id_pais" required>
                    <option value="none" selected disabled>Seleccione un país</option>
                    <?php
                    $con = new mysqli("localhost", "root", "", "juego_memoria") or die("Error al conectar a la base de datos");
                    $query = "SELECT * FROM pais";
                    try {
                        $resultado = $con->query($query);
                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<option value='" . $fila['id_pais'] . "'>" . $fila['nombre'] . "</option>";
                        }
                        $resultado->free();
                    } catch (mysqli_sql_exception $e) {
                        echo "Error al obtener los países: " . $e->getMessage();
                    } finally {
                        $con->close();
                    }
                    ?>
                </select>
            </div>

            <input type="submit" value="Registrarse" class="btn-submit">
            <a href="index.php" class="enlace-volver">Volver al inicio</a>
        </form>
    </div>

    <script>
        document.getElementById('formRegistro').addEventListener('submit', function (evento) {
            // Frenamos la recarga tradicional de la página
            evento.preventDefault();

            var nombre = document.getElementById('nombre').value;
            var contrasenia = document.getElementById('contrasenia').value;
            var fecha = document.getElementById('fecha_nacimiento').value;
            var pais = document.getElementById('id_pais').value;

            var parametros = "nombre=" + nombre + "&contrasenia=" + contrasenia + "&fecha_nacimiento=" + fecha + "&id_pais=" + pais;
            var peticion = new XMLHttpRequest();

            peticion.onreadystatechange = function () {
                if (peticion.readyState == 4 && peticion.status == 200) {

                    // Mostramos la respuesta del servidor
                    alert(peticion.responseText);

                    // Si el registro fue exitoso, limpiamos los campos
                    if (peticion.responseText.includes("correctamente")) {
                        document.getElementById('formRegistro').reset();
                    }
                }
            };

            peticion.open("POST", "procesar_registro.php", true);
            peticion.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            peticion.send(parametros);
        });
    </script>
</body>

</html>