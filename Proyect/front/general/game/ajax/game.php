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
        }
        $this->delete("collectiongame", array("Game_IGDB_id" => $game_id, "Collection_IGDB_id" => $id));
        $this->insert("collectiongame", array("Game_IGDB_id" => $game_id, "Collection_IGDB_id" => $id));
    }

    private function if_not_exists_insert_franchise($id, $name, $game_id) {
        $count = $this->select("franchise", "count(*) cant", false, array("IGDB_id" => $id))[0]["cant"];
        if (!$count) {
            $this->insert("franchise", array("IGDB_id" => $id, "name" => $name));
        }
        $this->delete("franchisegame", array("Game_IGDB_id" => $game_id, "Franchise_IGDB_id" => $id));
        $this->insert("franchisegame", array("Game_IGDB_id" => $game_id, "Franchise_IGDB_id" => $id));
    }

    private function if_not_exists_insert_engine($id, $name, $game_id) {
        $count = $this->select("engine", "count(*) cant", false, array("IGDB_id" => $id))[0]["cant"];
        if (!$count) {
            $this->insert("engine", array("IGDB_id" => $id, "name" => $name));
        }
        $this->delete("enginegame", array("Game_IGDB_id" => $game_id, "Engine_IGDB_id" => $id));
        $this->insert("enginegame", array("Game_IGDB_id" => $game_id, "Engine_IGDB_id" => $id));
    }

    private function if_not_exists_insert_genre($id, $name, $game_id) {
        $count = $this->select("genre", "count(*) cant", false, array("IGDB_id" => $id))[0]["cant"];
        if (!$count) {
            $this->insert("genre", array("IGDB_id" => $id, "name" => $name));
        }
        $this->delete("genregame", array("Game_IGDB_id" => $game_id, "Genre_IGDB_id" => $id));
        $this->insert("genregame", array("Game_IGDB_id" => $game_id, "Genre_IGDB_id" => $id));
    }

    private function insert_platform($id, $game_id, $release_date, $region) {
        $date = date('Y-m-d', $release_date);
        $this->delete("platformgame", array("Game_IGDB_id" => $game_id, "Platform_IGDB_id" => $id, "releasedDate" => $date, "region" => $region));
        $this->insert("platformgame", array("Game_IGDB_id" => $game_id, "Platform_IGDB_id" => $id, "releasedDate" => $date, "region" => $region));
    }

    /* END SQL querys */

    /* API */

    public function regist_game() {
        $id = $this->post_dat["id"];
        $body = "fields id, name, category, first_release_date, game_engines.name, game_type.id, cover.image_id, slug, release_dates.date, release_dates.platform.name, release_dates.release_region, ";
        $body .= "franchises.name,";
        $body .= "genres.name, collections.name, game_type;";
        $body .= "where id = " . $id . ";";

        $url = "https://api.igdb.com/v4/games";
        $game = $this->IGDB_API_con($url, $body)[0];

//        print_r($game);

        $values = array(
            "title" => $game["name"],
            "cover" => $game["cover"]["image_id"],
            "isSpinOff" => 0,
            "IGDB_url" => $game["slug"],
            "GameType_IGDB_id" => $game["game_type"]["id"],
            "first_release_date" => date('Y-m-d', $game["first_release_date"]),
        );
//        print_r($values);
        if ($this->post_dat["new"]) {
            $values["IGDB_id"] = $game["id"];
            $this->insert("game", $values);
        } else {
            $this->update("game", $values, array("IGDB_id" => $game["id"]));
        }
        foreach ($game["collections"] as $key => $collection) {
            $this->if_not_exists_insert_collection($collection["id"], $collection["name"], $game["id"]);
        }
        foreach ($game["franchises"] as $key => $franchise) {
            $this->if_not_exists_insert_franchise($franchise["id"], $franchise["name"], $game["id"]);
        }
        if (isset($game["game_engines"])) {
            foreach ($game["game_engines"] as $key => $engine) {
                $this->if_not_exists_insert_engine($engine["id"], $engine["name"], $game["id"]);
            }
        }
        foreach ($game["genres"] as $key => $genre) {
            $this->if_not_exists_insert_genre($genre["id"], $genre["name"], $game["id"]);
        }
        foreach ($game["release_dates"] as $key => $release_dates) {
//            print_r($release_dates);
            if (isset($release_dates["date"])) {
                $this->insert_platform($release_dates["platform"]["id"], $game["id"], $release_dates["date"], $release_dates["release_region"]);
            }
        }

        $this->result["newLocation"] = "index.php?page=game&id=" . $id;
    }

    /* End API */
}

$obj = new game($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
