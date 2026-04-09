<!-- -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtro de Jugadores</title>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = htmlspecialchars($_POST["nombre"]);
        $edad = $_POST["edad"];
        define("EDAD_MINIMA", 12);
        if ($edad <= EDAD_MINIMA) {
            echo "Lo sentimos " . $nombre . ", debes ser mayor de " . EDAD_MINIMA . " años para jugar.";
        } else {
            echo "Bienvenido " . $nombre . ", cumples los requisitos para jugar.<br>";
        }
        echo ('<a href="ejercicio_1.php">Volver al formulario</a>');
    } else {
        ?>
        <h1>Filtro de Jugadores</h1>
        <form action="" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required>
            <button type="submit">Verificar</button>
        </form>
        <?php
    }
    ?>

</body>

</html>