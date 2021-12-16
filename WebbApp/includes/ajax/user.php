<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of collection
 *
 * @author crist
 */
include './ajax.php';

class user extends ajax {

    public function signin() {
        $nombreCompleto = $this->op["nombreCompleto"];
        $nombreUsuario = $this->op["nombreUsuario"];
        $pass = md5($this->op["pass1"]);
        $email = $this->op["mail"];
        $sql = "INSERT INTO usuario (nombreCompleto, NombreUsuario, password, mail) VALUES ('" . $nombreCompleto . "','" . $nombreUsuario . "','" . $pass . "','" . $email . "')";
        if ($query = $this->get_query($sql)) {
            $this->result["success"] = 1;
            $this->result["alert"] = 1;
            $this->result["location"] = $this->url;
            $this->result["msg"] = "Usuario creado, iniciando sesión";
            $this->login();
        } else {
            $this->result["error"] = 1;
            $this->result["alert"] = 1;
            $this->result["msg"] = "Ha habido un error inesperado";
        }
    }

    public function login() {
        $nombreUsuario = $this->op["nombreUsuario"];
        $actual_link = $_SERVER['HTTP_HOST'];
        $pass = md5($this->op["pass1"]);
        $sql = "SELECT * FROM usuario WHERE NombreUsuario= '" . $nombreUsuario . "' AND password = '" . $pass . "'";
        if ($query = $this->get_query($sql)) {
            if ($row = $query->fetch_assoc()) {
                $obj = (object) array("id" => $row["idUsuario"], "name" => $row["nombreCompleto"], "mail" => $row["mail"], "logo" => $row["logo"]);
                setcookie("user_id", $row["idUsuario"], time() + 3600 * 24, "/");
            }
        }
        $this->result["reload"] = 1;
    }

    public function adquirir_juego() {
        $idUsuario = $this->op["idUsuario"];
        $idJuegoPlataforma = $this->op["idJuegoPlataforma"];
        $idDistribuidor = $this->op["idDistribuidor"];
        $fechaAdquisicion = $this->op["fechaAdquisicion"];
        $fecha = $fechaAdquisicion;
        $coste = $this->op["coste"];
        $obs = $this->op["obs"];

        $array = array("idUsuario" => $idUsuario, "idJuegoPlataforma" => $idJuegoPlataforma, "idDistribuidor" => $idDistribuidor, "fechaAdquisicion" => "'" . $fecha . "'", "coste" => $coste, "observacion" => "'" . $obs . "'");
        if ($this->insert("usuariojuegoplataforma", $array)) {
            $this->result["success"] = 1;
            $this->result["alert"] = 1;
            $this->result["location"] = $this->url . "perfil/mis_juegos";
            $this->result["msg"] = "Juego adquirido";
        } else {
            $this->result["error"] = 1;
            $this->result["alert"] = 1;
            $this->result["msg"] = "Ha ocurrido un error inesperado";
        }
    }

    public function adquirir_partida() {
        $idJuegoPlataforma = $this->op["idJuegoPlataforma"];
        $fechaIni = $this->op["fechaIni"];
        $obs = $this->op["obs"];
        if (($this->op["fechaFin"])) {
            $fechaFin = $this->op["fechaFin"];
            $array = array("idUsuarioJuegoPlataforma" => $idJuegoPlataforma, "fechaInicio" => "'" . $fechaIni . "'", "fechaFin" => "'" . $fechaFin . "'", "observacion" => "'" . $obs . "'");
        } else {
            $array = array("idUsuarioJuegoPlataforma" => $idJuegoPlataforma, "fechaInicio" => "'" . $fechaIni . "'", "fechaFin" => "NULL", "observacion" => "'" . $obs . "'");
        }
        if ($this->insert("partida", $array)) {
            $this->result["success"] = 1;
            $this->result["alert"] = 1;
            $this->result["reload"] = 1;
            $this->result["msg"] = "Juego adquirido";
        } else {
            $this->result["error"] = 1;
            $this->result["alert"] = 1;
            $this->result["msg"] = "Ha ocurrido un error inesperado";
        }
    }

    public function add_time() {
        $idUsuarioJuegoPlataforma = $this->op["idUsuarioJuegoPlataforma"];
        $idPartida = $this->op["idPartida"];
        $fechaIni = $this->op["fechaIni"];
        $fechaFin = $this->op["fechaFin"];
        $array = array("idUsuarioJuegoPlataforma" => $idUsuarioJuegoPlataforma, "idPartida" => $idPartida, "fechaInicio" => "'" . $fechaIni . "'", "fechaFin" => "'" . $fechaFin . "'");
        if ($this->insert("tiempojuego", $array)) {
            $this->result["success"] = 1;
            $this->result["alert"] = 1;
            $this->result["reload"] = 1;
            $this->result["msg"] = "Juego adquirido";
        } else {
            $this->result["error"] = 1;
            $this->result["alert"] = 1;
            $this->result["msg"] = "Ha ocurrido un error inesperado";
        }
    }

    public function link_plat() {
        $idUsuario = $this->op["id2"];
        $idPlataforma = $this->op["id1"];
        $array = array("idUsuario" => $idUsuario, "idPlataforma" => $idPlataforma);
        if ($this->insert("usuarioplataforma", $array)) {
            $this->result["success"] = 1;
            $this->result["alert"] = 1;
            $this->result["location"] = $this->url . "perfil/mis_plataformas";
            $this->result["msg"] = "Plataforma adquirida";
        } else {
            $this->result["error"] = 1;
            $this->result["alert"] = 1;
            $this->result["msg"] = "Ha ocurrido un error inesperado";
        }
    }

    public function unlink_plat() {
        $idUsuario = $this->op["id2"];
        $idPlataforma = $this->op["id1"];
        $array = array("idUsuario" => $idUsuario, "idPlataforma" => $idPlataforma);
        if ($this->delete("usuarioplataforma", $array)) {
            $this->result["success"] = 1;
            $this->result["alert"] = 1;
            $this->result["reload"] = 1;
            $this->result["msg"] = "Plataforma eliminada";
        } else {
            $this->result["error"] = 1;
            $this->result["alert"] = 1;
            $this->result["msg"] = "Ha ocurrido un error inesperado";
        }
    }

}

session_start(); //abre $_SESSION
$ajax = new user($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();

echo json_encode($ajax->result);
