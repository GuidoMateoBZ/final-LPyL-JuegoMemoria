<?php

class GestorPartida
{
    private $con;

    public function __construct()
    {
        $this->con = new mysqli("localhost", "root", "", "juego_memoria");
        // Verifica si hay error interno de conexión
        if ($this->con->connect_error) {
            die("Error de conexión a la base de datos.");
        }
    }

    // Cerramos la conexión siempre que el objeto se destruya
    public function __destruct()
    {
        if ($this->con) {
            $this->con->close();
        }
    }

    /**
     * Obtiene los detalles de una única partida usando su ID
     */
    public function obtenerPartidaPorId($id_partida)
    {
        $id_partida = $this->con->real_escape_string($id_partida);
        $query = "SELECT * FROM partida WHERE id_partida = '$id_partida'";

        try {
            $resultado = $this->con->query($query);
            $partida = $resultado->fetch_assoc();
            $resultado->free();
            return ["exito" => true, "datos" => $partida];
        } catch (mysqli_sql_exception $e) {
            return ["exito" => false, "mensaje" => "Error en BD: " . $e->getMessage()]; 
        }
    }

    /**
     * Obtiene el historial histórico de todas las partidas jugadas por un usuario en particular,
     * obteniendo detalles de su desempeño a través de los JOINs.
     * Ordenadas de la más reciente a la más antigua.
     */
    public function obtenerPartidasPorUsuario($id_usuario)
    {
        $id_usuario = $this->con->real_escape_string($id_usuario);
        // Hacemos JOIN entre partida y la tabla pivote usuario_partida, sumado al texto del resultado
        $query = "
            SELECT p.id_partida, p.fecha, p.dificultad, p.tiempo_jugado, 
                   up.puntaje, up.pares_descubiertos, up.intentos, r.nombre AS resultado_final
            FROM partida p
            INNER JOIN usuario_partida up ON p.id_partida = up.id_partida
            INNER JOIN resultado r ON up.id_resultado = r.id_resultado
            WHERE up.id_usuario = '$id_usuario'
            ORDER BY p.fecha DESC
        ";

        $partidas = [];
        try {
            $resultado = $this->con->query($query);
            while ($fila = $resultado->fetch_assoc()) {
                $partidas[] = $fila;
            }
            $resultado->free();
            return ["exito" => true, "datos" => $partidas];
        } catch (mysqli_sql_exception $e) {
            return ["exito" => false, "mensaje" => "Error en BD: " . $e->getMessage()]; 
        }
    }

    /**
     * Obtiene las partidas donde dos usuarios en específico jugaron JUNTOS.
     * Ordenadas de la más reciente a la más antigua.
     */
    public function obtenerPartidasEntreDosUsuarios($id_usuario1, $id_usuario2)
    {
        $id_usuario1 = $this->con->real_escape_string($id_usuario1);
        $id_usuario2 = $this->con->real_escape_string($id_usuario2);

        // Buscamos las partidas que existen en usuario_partida tanto para U1 como para U2
        $query = "
            SELECT p.*
            FROM partida p
            INNER JOIN usuario_partida up1 ON p.id_partida = up1.id_partida
            INNER JOIN usuario_partida up2 ON p.id_partida = up2.id_partida
            WHERE up1.id_usuario = '$id_usuario1' 
              AND up2.id_usuario = '$id_usuario2'
            ORDER BY p.fecha DESC
        ";

        $partidas_combinadas = [];
        try {
            $resultado = $this->con->query($query);
            while ($fila = $resultado->fetch_assoc()) {
                $partidas_combinadas[] = $fila;
            }
            $resultado->free();
            return ["exito" => true, "datos" => $partidas_combinadas];
        } catch (mysqli_sql_exception $e) {
            return ["exito" => false, "mensaje" => "Error en BD: " . $e->getMessage()];
        }
    }
    /**
     * Extrae de la BD la última partida que estos dos usuarios hayan jugado específicamente JUNTOS,
     * obteniendo qué puntaje sacó cada uno. 
     */
    public function obtenerUltimaPartidaJuntos($id_usuario1, $id_usuario2) {
        $id_usuario1 = $this->con->real_escape_string($id_usuario1);
        $id_usuario2 = $this->con->real_escape_string($id_usuario2);
        
        $query = "
            SELECT p.*, up1.puntaje AS puntaje1, up2.puntaje AS puntaje2
            FROM partida p
            INNER JOIN usuario_partida up1 ON p.id_partida = up1.id_partida
            INNER JOIN usuario_partida up2 ON p.id_partida = up2.id_partida
            WHERE up1.id_usuario = '$id_usuario1' AND up2.id_usuario = '$id_usuario2'
            ORDER BY p.fecha DESC LIMIT 1
        ";
        try {
            $resultado = $this->con->query($query);
            $fila = $resultado->fetch_assoc();
            $resultado->free();
            return ["exito" => true, "datos" => $fila];
        } catch (mysqli_sql_exception $e) {
            return ["exito" => false, "mensaje" => $e->getMessage()];
        }
    }

    /**
     * Extrae la última partida que jugó un usuario determinado,
     * indicando la fecha y recuperando el nombre de su contrincante a través de un JOIN con la tabla de usuarios.
     */
    public function obtenerUltimaPartidaUsuarioConOponente($id_usuario) {
        $id_usuario = $this->con->real_escape_string($id_usuario);
        
        $query = "
            SELECT p.*, u2.nombre_usuario AS oponente, up1.puntaje, up1.pares_descubiertos, up1.intentos
            FROM partida p
            INNER JOIN usuario_partida up1 ON p.id_partida = up1.id_partida
            INNER JOIN usuario_partida up2 ON p.id_partida = up2.id_partida
            INNER JOIN usuario u2 ON up2.id_usuario = u2.id_usuario
            WHERE up1.id_usuario = '$id_usuario' AND up2.id_usuario != '$id_usuario'
            ORDER BY p.fecha DESC LIMIT 1
        ";
        try {
            $resultado = $this->con->query($query);
            $fila = $resultado->fetch_assoc();
            $resultado->free();
            return ["exito" => true, "datos" => $fila];
        } catch (mysqli_sql_exception $e) {
            return ["exito" => false, "mensaje" => $e->getMessage()];
        }
    }

}
?>