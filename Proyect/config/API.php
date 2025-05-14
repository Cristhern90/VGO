<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of API
 *
 * @author Cristian
 */
include 'AJAX.php';

class API extends AJAX {

    protected $data_api = array("client" => "", "client_secret" => "", "token" => "");

    #[\Override]
    public function __construct($post_dat) {
        parent::__construct($post_dat);
        $this->read_API_Json($this->url_json_bbdd . "API.json"); //read JSON of API
    }

    protected function read_API_Json($fileName) {
        $json_file = file_get_contents($fileName);
        $json_array = json_decode($json_file, true);

        foreach ($json_array as $key => $json) {
            $this->data_api[$key] = $json;
        }
    }

    protected function IGDB_API_con($url, $body) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => '', CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 0, CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                'Client-ID: ' . $this->data_api["client"],
                'Authorization: Bearer ' . $this->data_api["token"],
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

//        print_r($response);

        return json_decode($response, true);
    }

    /* Get games by propertie */

    protected function best_games_of($filter, $values, $no_ids, $exclusive = 0) {
        $body = "fields id, name, cover.url, platforms, category, aggregated_rating, aggregated_rating_count, url;";
        $val = $values;
        if($exclusive == 0){
            $val = "[".$values."]";
        }
        $body .= "where " . $filter . " = " . $val . ($no_ids ? " & id != (" . $no_ids . ")" : "") . " & category = (0,8,9) & aggregated_rating_count > 0";
        if($filter == "involved_companies.company"){
            $body .= " & involved_companies.developer = true";
        }
        $body .= ";";
        $body .= "sort aggregated_rating_count desc;";
        $body .= "limit 500;";
        
//        echo $body;

        $url = "https://api.igdb.com/v4/games";

        $games = $this->IGDB_API_con($url, $body);
        
//        print_r($games);

        $array_games = array();

        foreach ($games as $key => $game) {
//            $rat = number_format($game["aggregated_rating"],4,".");
            $rat = number_format($game["aggregated_rating"], 4);
            if (!isset($array_games[$rat . $game["id"]])) {
                $array_games[$rat . $game["id"]] = $game;
            }
        }
        krsort($array_games);

        $firts_games = array();
        $count = 0;
        $html = "";
        foreach ($array_games as $key => $game_) {
            if ($count >= 12) {
                break;
            }
            array_push($firts_games, $game);
            $html .= '<div class="col-4 col-md-3 col-xl-2 p-1 game_element" data-id="' . $game_["id"] . '">';
            $html .= '<div class="border border-3 w-100 p-1">';
            $html .= '<div class="row m-0 mb-1">';
            $html .= '<button class="col-12 col-lg-6" onclick="window.open(\'' . $game_["url"] . '\')">IGDB web</button>';
            $html .= '<button class="col-12 col-lg-6" onclick="regist_game(\''.$game_["id"].'\',1)">Registrar</button>';
            $html .= "</div>";
            $html .= '<img src="' . str_replace("t_thumb", "t_cover_med", $game_["cover"]["url"]) . '" class="w-100">';
            $html .= '<div>' . $game_["name"] . " (".$game_["aggregated_rating"].") (".$game_["aggregated_rating_count"].")". '</div>';
            $html .= "</div>";
            $html .= "</div>";
            $count++;
        }
        return $html;
    }

    /* End Get games by propertie */

    /* Regist game */

    public function save_game($id) {
        $body = "fields *;";
        $body .= "where id = ".$id.";";
    }

    /* End Regist game */
}
