<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Memoria - Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="main-title">Inicio Sesión</h1>

    <div class="formularios-wrapper">
        <!-- Formulario Jugador 1 -->
        <div class="registro-container">
            <h1>Jugador 1</h1>
            <form id="formUsuario1">
                <div class="form-group">
                    <label for="nombre1">Nombre de usuario</label>
                    <input type="text" id="nombre1" name="nombre1" required>
                </div>
                <div class="form-group">
                    <label for="contrasenia1">Contraseña</label>
                    <input type="password" id="contrasenia1" name="contrasenia1" required>
                </div>
                <input type="submit" value="Iniciar Sesión" class="btn-submit">
            </form>
        </div>

        <!-- Formulario Jugador 2 -->
        <div class="registro-container">
            <h1>Jugador 2</h1>
            <form id="formUsuario2">
                <div class="form-group">
                    <label for="nombre2">Nombre de usuario</label>
                    <input type="text" id="nombre2" name="nombre2" required>
                </div>
                <div class="form-group">
                    <label for="contrasenia2">Contraseña</label>
                    <input type="password" id="contrasenia2" name="contrasenia2" required>
                </div>
                <input type="submit" value="Iniciar Sesión" class="btn-submit">
            </form>
        </div>
    </div>

    <a href="registro.php" class="enlace-volver-inverso" style="text-align: center; margin-top: 30px;">¿No tenés cuenta?
        Regístrate acá</a>

    <script>
        function iniciarSesion(evento, numUsuario) {
            evento.preventDefault();

            var nombre = document.getElementById('nombre' + numUsuario).value;
            var contrasenia = document.getElementById('contrasenia' + numUsuario).value;

            var parametros = "nombre=" + nombre + "&contrasenia=" + contrasenia + "&numUsuario=" + numUsuario;

            var peticion = new XMLHttpRequest();
            peticion.onreadystatechange = function () {
                if (peticion.readyState == 4 && peticion.status == 200) {
                    var respuesta = JSON.parse(peticion.responseText);
                    alert(respuesta.mensaje);

                    if (respuesta.exito) {
                        sessionStorage.setItem('usuario' + numUsuario, nombre);
                        if (sessionStorage.getItem('usuario1') && sessionStorage.getItem('usuario2')) {
                            window.location.href = "configuracion_partida.php";
                        }
                    }
                }
            };
            peticion.open("POST", "procesar_login.php", true);
            peticion.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            peticion.send(parametros);
        }

        // Escuchamos el form del Usuario 1 y llamamos a la función
        document.getElementById('formUsuario1').addEventListener('submit', function (evento) {
            iniciarSesion(evento, 1);
        });

        // Escuchamos el form del Usuario 2 y llamamos a la misma función
        document.getElementById('formUsuario2').addEventListener('submit', function (evento) {
            iniciarSesion(evento, 2);
        });
    </script>
</body>

</html>