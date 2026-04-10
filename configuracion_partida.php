<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Partida</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Configuración de Partida</h1>
    <!-- Mostrar información de los jugadores que iniciaron sesión -->
    <div>
        <p>Jugador 1: <?php echo $_SESSION['usuario1']['nombre_usuario']; ?></p>
    </div>
    <div>
        <p>Jugador 2: <?php echo $_SESSION['usuario2']['nombre_usuario']; ?></p>
    </div>
    <!-- Formulario para configurar la partida -->
    <form id="formConfiguracion">
        <select name="dificultad" id="dificultad">
            <option value="8">Baja (4 pares)</option>
            <option value="16">Media (8 pares)</option>
            <option value="32">Alta (16 pares)</option>
        </select>
        <select name="tiempo" id="tiempo">
            <option value="240">4 minutos</option>
            <option value="900">15 minutos</option>
            <option value="1200">20 minutos</option>
            <option value="0">Sin límite de tiempo</option>
        </select>
        <input type="submit" value="Iniciar Partida">
    </form>
    <a href="index.php" onclick="cerrarSesion()">Salir</a>
</body>

</html>

<script>
    function cerrarSesion() {
        sessionStorage.removeItem('usuario1');
        sessionStorage.removeItem('usuario2');
    }
</script>