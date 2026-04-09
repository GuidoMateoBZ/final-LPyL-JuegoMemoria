<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Jugador - Select Jugadores</title>
</head>

<body>
    <?php
    function insertarJugador()
    {
        $nombre = 'guidomateobz';
        $edad = 22;
        $id_pais = 001;
        $con = new mysqli("localhost", "root", "", "juego_memoria") or die("Error al conectar a la base de datos");
        $query = "INSERT INTO usuario (nombre, edad, id_pais) VALUES ('$nombre', '$edad', '$id_pais')";

        try {
            $con->query($query);
            echo "Jugador insertado correctamente";
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                echo "<script>alert('El nombre de usuario ya existe');</script>";
            } else {
                echo "Error nro " . $e->getCode() . " al insertar el jugador";
            }
        }
        $con->close();
    }

    function eliminarJugador()
    {
        $con = new mysqli("localhost", "root", "", "juego_memoria") or die("Error al conectar a la base de datos");
        $query = "DELETE FROM usuario WHERE id_pais = 1";
        $con->query($query) or die("Error al eliminar el jugador");
        $con->close();
        echo "Jugador eliminado correctamente";
    }

    if (isset($_POST['btnInsertar'])) {
        insertarJugador();
    }

    if (isset($_POST['btnEliminar'])) {
        eliminarJugador();
    }

    ?>
    <h1>Lista de Jugadores</h1>
    <form action="" method="post">
        <button name="btnInsertar" type="submit">Insertar Jugador</button>
    </form>
    <br>
    <form action="" method="post">
        <button name="btnEliminar" type="submit">Eliminar Jugador</button>
    </form>

    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Pais</th>
        </tr>
        <?php
        $con = new mysqli("localhost", "root", "", "juego_memoria") or die("Error al conectar a la base de datos");
        $query = "SELECT * FROM usuario";
        $result = $con->query($query);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['edad'] . "</td>";
                echo "<td>" . $row['id_pais'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr>";
            echo "<td colspan='3'>No hay jugadores</td>";
            echo "</tr>";
        }
        $result->free();
        $con->close();
        ?>
    </table>
</body>

</html>