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

class top extends ajax {

    public function set_element() {
        $idReferencia = $this->op["id1"];
        $idTop = $this->op["id2"];
        $idTopPosicion = $this->op["id3"];
//        echo $idTopPosicion;
        if ($idTopPosicion != "undefined") {
            $query = $this->select("topelemento", "COUNT(*) count", array("idReferencia" => $idReferencia, "idTop" => $idTop));
            if ($row = $query->fetch_assoc()) {
                if ($row["count"]) {
                    $this->update("topelemento", array("idTopPosicion" => $idTopPosicion), array("idReferencia" => $idReferencia, "idTop" => $idTop));
                } else {
                    $this->insert("topelemento", array("idReferencia" => $idReferencia, "idTop" => $idTop, "idTopPosicion" => $idTopPosicion));
                }
            }
        } else {
            $this->delete("topelemento", array("idReferencia" => $idReferencia, "idTop" => $idTop));
        }
    }

    public function new_top() {
        $name = $this->op["name"];
        $cantidad = $this->op["cantidad"];
        if ($this->insert("top", array("name" => "'" . $name . "'", "cantidad" => $cantidad, "tabla" => "'juego'", "idUsuario" => $_COOKIE["user_id"]))) {
            $sql = "SELECT idTop FROM top WHERE name = '" . $name . "' AND cantidad = " . $cantidad . " AND idUsuario = " . $_COOKIE["user_id"] . " order by idTop DESC LIMIT 1";
            if ($query = $this->get_query($sql)) {
                if ($row = $query->fetch_assoc()) {
                    $idTop = $row["idTop"];
                    $sql_i = "INSERT INTO topposicion (idTop,name,posicion) VALUES ";
                    for ($index = 1; $index <= $cantidad; $index++) {
                        if ($index > 1) {
                            $sql_i .= ", ";
                        }
                        $sql_i .= "(" . $idTop . ",'" . $index . "'," . $index . ")";
                    }
                    if ($query = $this->get_query($sql_i)) {
                        $this->result["reload"] = 1;
                    }
                }
            }
        }
    }

    public function del_top() {
        $idTop = $this->op["id"];
        $this->delete("topelemento", array("idTop" => $idTop));
        $this->delete("topposicion", array("idTop" => $idTop));
        $this->delete("toprequisito", array("idTop" => $idTop));
        $this->delete("top", array("idTop" => $idTop));
        $this->result["location"] = $this->url . "perfil/tops";
    }

    public function add_req() {
        $idTop = $this->op["idTop"];
        $req = $this->op["req"];
        $tipo = $this->op["tipo"];
        $valor1 = $this->op["valor1"];
        $valor2 = $this->op["valor2"];
        $req_arr = explode("_", $req);
        if ($this->insert("toprequisito", array("idTop" => $idTop, "tabla" => "'" . $req_arr[0] . "'", "name" => "'" . $req_arr[1] . "'", "tipo" => "'" . $tipo . "'", "valor1" => "'" . $valor1 . "'", "valor2" => ($valor2 ? "'" . $valor2 . "'" : "NULL")))) {
            $this->result["reload"] = 1;
        }
    }

    public function del_req() {
        $idTopRequisito = $this->op["id"];
        if ($this->delete("toprequisito", array("idTopRequisito" => $idTopRequisito))) {
            $this->result["reload"] = 1;
        }
    }

}

session_start(); //abre $_SESSION
$ajax = new top($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();

echo json_encode($ajax->result);
