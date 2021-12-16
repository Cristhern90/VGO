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

class cronologias extends ajax {

    public function nueva_cronologia() {
        $nombre = $this->op["nombre"];
        if ($this->insert("cronologia", array("name" => "'" . $nombre . "'"))) {
            $this->result["success"] = 1;
            $this->result["reload"] = 1;
        }
    }

    public function nuevo_grupo() {
        $nombre = $this->op["nombre"];
        $orden = $this->op["orden"];
        $idCronologia = $this->op["idCronologia"];
        if ($this->insert("grupocronologia", array("name" => "'" . $nombre . "'", "orden" => $orden, "idCronologia" => $idCronologia))) {
            $this->result["success"] = 1;
            $this->result["reload"] = 1;
        }
    }

    public function add_serie() {
        $idSerie = $this->op["idSerie"];
        $idCronologia = $this->op["idCronologia"];
        if ($this->insert("cronologiaserie", array("idSerie" => $idSerie, "idCronologia" => $idCronologia))) {
            $this->result["success"] = 1;
            $this->result["reload"] = 1;
        } else {
            $this->result["alert"] = 1;
            $this->result["str"] = json_encode($this->op);
        }
    }

    public function nueva_linea() {
        $nombre = $this->op["nombre"];
        $orden = $this->op["orden"];
        $idGrupoCronologia = $this->op["idGrupoCronologia"];
        if ($this->insert("lineacronologia", array("titulo" => "'" . $nombre . "'", "orden" => $orden, "idGrupoCronologia" => $idGrupoCronologia))) {
            $this->result["success"] = 1;
            $this->result["reload"] = 1;
        }
    }

    public function add_juego() {
        $idGrupoCronologia = $this->op["idGrupoCronologia"];
        $idCronologia = $this->op["idCronologia"];
        $idLineaCronologia = ($this->op["idLineaCronologia"] ? "'" . $this->op["idLineaCronologia"] . "'" : "NULL");
        $idJuego = $this->op["idJuego"];
        $desp = $this->op["desp"];
        $posicion = ($this->op["posicion"] ? $this->op["posicion"] : "NULL");
        $observacion = ($this->op["observacion"] ? "'" . $this->op["observacion"] . "'" : "NULL");

        $bol = false;
        if ($desp) {
            $where = array("idCronologia" => $idCronologia, "idGrupoCronologia" => $idGrupoCronologia, "idLineaCronologia" => $idLineaCronologia, "posicion" => array(">=", $posicion));
            if ($this->update("juegocronologia", array("posicion" => "posicion + 1"), $where)) {
                $bol = true;
            }
        } else {
            $bol = true;
        }

        if ($bol) {
            $arr_vals = array("idCronologia" => $idCronologia, "idGrupoCronologia" => $idGrupoCronologia, "idLineaCronologia" => $idLineaCronologia, "idJuego" => $idJuego, "posicion" => $posicion, "observacion" => $observacion);
            if ($this->insert("juegocronologia", $arr_vals)) {
                $this->result["success"] = 1;
                $this->result["reload"] = 1;
            }
        }
    }

    public function del_juego() {
        $idJuegoCronologia = $this->op["idJuegoCronologia"];
        $idCronologia = $this->op["idCronologia"];
        $idGrupoCronologia = $this->op["idGrupoCronologia"];
        $idLineaCronologia = $this->op["idLineaCronologia"];
        $posicion = $this->op["posicion"];
        $desp = $this->op["desp"];
        $table = "juegocronologia";
        if ($this->delete($table, array("idJuegoCronologia" => $idJuegoCronologia))) {
            if ($desp) {
                $where = array("idCronologia" => $idCronologia, "idGrupoCronologia" => $idGrupoCronologia, "idLineaCronologia" => $idLineaCronologia, "posicion" => array(">=", $posicion));
                if ($this->update($table, array("posicion" => "posicion - 1"), $where)) {
                    $this->result["success"] = 1;
                    $this->result["reload"] = 1;
                }
            } else {
                $this->result["success"] = 1;
                $this->result["reload"] = 1;
            }
        }
    }

}

session_start(); //abre $_SESSION
$ajax = new cronologias($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();

echo json_encode($ajax->result);
