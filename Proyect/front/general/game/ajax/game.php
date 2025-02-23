<?php

include '../../../../config/API.php';

/**
 * Description of game
 *
 * @author crist
 */
class game extends API {

    #[\Override]
    public function __construct($post_dat) {
        $this->url_json_bbdd = "../../../../config/dades/";
        parent::__construct($post_dat);
    }

    /* SQL querys */

    private function if_not_exists_insert_collection($id, $name, $game_id) {
        $count = $this->select("collection", "count(*) cant", false, array("IGDB_id" => $id))[0]["cant"];
        if (!$count) {
            $this->insert("collection", array("IGDB_id" => $id, "name" => $name));
            $this->insert("collectiongame", array("Game_IGDB_id" => $game_id, "Collection_IGDB_id" => $id));
        }
    }

    private function if_not_exists_insert_engine($id, $name, $game_id) {
        $count = $this->select("engine", "count(*) cant", false, array("IGDB_id" => $id))[0]["cant"];
        if (!$count) {
            $this->insert("engine", array("IGDB_id" => $id, "name" => $name));
            $this->insert("enginegame", array("Game_IGDB_id" => $game_id, "Collection_IGDB_id" => $id));
        }
    }

    /* END SQL querys */

    /* API */

    public function regist_game() {
        $id = $this->post_dat["id"];
        $body = "fields id, name, category, game_engines.name, cover.url, url, release_dates.date, release_dates.platform, release_dates.release_region, genres, collections.name, game_type;";
        $body .= "where id = " . $id . ";";

        $url = "https://api.igdb.com/v4/games";
        $game = $this->IGDB_API_con($url, $body);
        
        $values = array(
            "IGDB_id" => $game["id"],
            "title" => $game["name"],
            "cover" => $game["cover"]["image_id"],
            "isSpinOff" => 0,
            "IGDB_url" => $game["url"],
            "GameType_IGDB_id" => $game["game_type"]["id"],
        );
//        print_r($game);
        $this->insert("game", $values);
        if_not_exists_insert_collection($game["collections"]["id"], $game["collections"]["name"], $game["id"]);
    }

    /* End API */
}

$obj = new game($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
