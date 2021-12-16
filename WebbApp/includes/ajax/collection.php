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

class collection extends ajax {

    public function act_serie() {
        $id = $this->op["id"];
//        $id = 359;
        $query = "fields collection.name;where id = " . $id . ";";
//        $query = "fields collection.name;where id = 359;";
        $response = json_decode($this->API_conection("games", $query));
        foreach ($response as $key => $serie) {
            if (isset($serie->collection)) {
                $serie_id = $serie->collection->id;
                $serie_name = $serie->collection->name;

                $ids = $this->existing_ids("serie");
                if (!in_array($serie_id, $ids)) {
                    $array_serie = array("idSerie" => $serie_id, "name" => "'" . addslashes($serie_name) . "'");
                    $this->insert("serie", $array_serie);
                }

                if (!$this->existing_2_id("juegoserie", "idJuego", $id, "idSerie", $serie_id)) {
                    $array_juego_serie = array("idSerie" => $serie_id, "idJuego" => $id);
                    $this->insert("juegoserie", $array_juego_serie);
                }
        $this->result["msg"] = "Serie actualizada";
            }else{
                $this->result["msg"] = "Este juego no tiene una serie asignada en IGDB";
            }
        }
        $this->result["success"] = 1;
        $this->result["alert"] = 1;
        $this->result["reload"] = 1;
    }
    
    public function set_serie(){
        $this->set_session();
        $this->result["reload"] = 0;
        $this->result["location"] = $this->url.'/juegos';
    }

}

session_start(); //abre $_SESSION
$ajax = new collection($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();

echo json_encode($ajax->result);
