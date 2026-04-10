<?php

class Usuario
{
    private $id_usuario;
    private $nombre_usuario;
    private $contrasenia;
    private $id_pais;
    private $fecha_nacimiento;

    public function __construct($id_usuario = null, $nombre_usuario = null, $contrasenia = null, $id_pais = null, $fecha_nacimiento = null)
    {
        $this->id_usuario = $id_usuario;
        $this->nombre_usuario = $nombre_usuario;
        $this->contrasenia = $contrasenia;
        $this->id_pais = $id_pais;
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    // Getters
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }
    public function getNombreUsuario()
    {
        return $this->nombre_usuario;
    }
    public function getContrasenia()
    {
        return $this->contrasenia;
    }
    public function getIdPais()
    {
        return $this->id_pais;
    }
    public function getFechaNacimiento()
    {
        return $this->fecha_nacimiento;
    }

    // Setters
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }
    public function setNombreUsuario($nombre_usuario)
    {
        $this->nombre_usuario = $nombre_usuario;
    }
    public function setContrasenia($contrasenia)
    {
        $this->contrasenia = $contrasenia;
    }
    public function setIdPais($id_pais)
    {
        $this->id_pais = $id_pais;
    }
    public function setFechaNacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function registrar()
    {
        $con = new mysqli("localhost", "root", "", "juego_memoria");
        if ($con->connect_error) {
            return "Error de conexión a la base de datos.";
        }

        $query = "INSERT INTO usuario (nombre_usuario, contrasenia, id_pais, fecha_nacimiento) 
                  VALUES ('$this->nombre_usuario', '$this->contrasenia', '$this->id_pais', '$this->fecha_nacimiento')";

        try {
            $con->query($query);
            $con->close();
            return "Jugador registrado correctamente";
        } catch (mysqli_sql_exception $e) {
            $con->close();
            if ($e->getCode() == 1062) {
                return "El nombre de usuario ya está en uso, por favor elija otro.";
            } else {
                return "Error en la base de datos: " . $e->getMessage();
            }
        }
    }

    public function iniciarSesion()
    {
        $con = new mysqli("localhost", "root", "", "juego_memoria");
        if ($con->connect_error) {
            return "Error de conexión a la base de datos.";
        }

        $query = "SELECT * FROM usuario WHERE nombre_usuario = '$this->nombre_usuario'";
        try {
            $resultado = $con->query($query);
            $usuario = $resultado->fetch_assoc();
            $respuesta = ["exito" => false, "mensaje" => ""];
            if ($usuario) {
                if (password_verify($this->contrasenia, $usuario['contrasenia'])) {
                    $this->id_usuario = $usuario['id_usuario'];
                    $respuesta["exito"] = true;
                    $respuesta["mensaje"] = "Login exitoso";
                } else {
                    $respuesta["mensaje"] = "Contraseña incorrecta";
                }
            } else {
                $respuesta["mensaje"] = "Usuario no encontrado";
            }
            $resultado->free();
            $con->close();
            return $respuesta;
        } catch (mysqli_sql_exception $e) {
            $con->close();
            return ["exito" => false, "mensaje" => "Error en la base de datos: " . $e->getMessage()];
        }
    }
}
?>