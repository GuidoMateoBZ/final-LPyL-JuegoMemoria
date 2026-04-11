<?php

class Partida
{
    private $id_partida;
    private $fecha;
    private $dificultad;
    private $tiempo_jugado;

    public function __construct($id_partida = null, $fecha = null, $dificultad = null, $tiempo_jugado = null)
    {
        $this->id_partida = $id_partida;
        $this->fecha = $fecha;
        $this->dificultad = $dificultad;
        $this->tiempo_jugado = $tiempo_jugado;
    }

    // Getters
    public function getIdPartida()
    {
        return $this->id_partida;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getDificultad()
    {
        return $this->dificultad;
    }
    public function getTiempoJugado()
    {
        return $this->tiempo_jugado;
    }

    // Setters
    public function setIdPartida($id_partida)
    {
        $this->id_partida = $id_partida;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    public function setDificultad($dificultad)
    {
        $this->dificultad = $dificultad;
    }
    public function setTiempoJugado($tiempo_jugado)
    {
        $this->tiempo_jugado = $tiempo_jugado;
    }
    public function guardarPartida()
    {
        $con = new mysqli("localhost", "root", "", "juego_memoria");
        if ($con->connect_error) {
            return ["exito" => false, "mensaje" => "Error de conexión a la base de datos."];
        }

        // Si no se pasó una fecha, la inicializamos con la fecha y hora actual de PHP
        if ($this->fecha == null) {
            $this->fecha = date('Y-m-d H:i:s');
        }

        $query = "INSERT INTO partida (fecha, dificultad, tiempo_jugado) 
                  VALUES ('$this->fecha', '$this->dificultad', '$this->tiempo_jugado')";

        try {
            $con->query($query);
            // Fundamental: Capturar y guardar el ID auto_generado de esta partida
            $this->id_partida = $con->insert_id;

            $con->close();
            return [
                "exito" => true,
                "mensaje" => "Partida registrada correctamente.",
                "id_partida" => $this->id_partida
            ];
        } catch (mysqli_sql_exception $e) {
            $con->close();
            return [
                "exito" => false,
                "mensaje" => "Error en la base de datos: " . $e->getMessage()
            ];
        }
    }
}
?>