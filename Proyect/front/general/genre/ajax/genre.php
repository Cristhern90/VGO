<?php

include '../../../../config/API.php';

/**
 * Description of platform
 *
 * @author Cristian
 */
class platform extends API {

    #[\Override]
    public function __construct($post_dat) {
        $this->url_json_bbdd = "../../../../config/dades/";
        parent::__construct($post_dat);
    }

    /* AJAX call functions */

    public function download_new_gens() {
        $ids = $this->select("genre", "GROUP_CONCAT(IGDB_id ORDER BY IGDB_id ASC SEPARATOR ', ') ids")[0]["ids"];
        if ($this->act_gen($ids, 1)) {
            $this->result["reload"] = 1; //send reload action
        } else {
            $this->result["errorCode"] = 3;
        }
    }

    public function act_all_genss() {
        if ($this->act_gen()) {
            $this->result["reload"] = 1; //send reload action
        } else {
            $this->result["errorCode"] = 3;
        }
    }

    public function act_specific_genss() {
        if ($this->act_gen($this->op["id"])) {
            $this->result["reload"] = 1; //send reload action
        } else {
            $this->result["errorCode"] = 3;
        }
    }

    /* END AJAX call functions */


    /* API querys */

    private function act_gen($ids = "", $inverse = 0) {
        $url = "https://api.igdb.com/v4/genres";
        $body = "fields id, name;";
        if ($ids) {
            if (is_array($ids)) {
                $text_ids = "";
                foreach ($ids as $key => $id) {
                    $text_ids .= ($key ? ", " : "") . $id;
                }
                $body .= "where id " . ($inverse ? "!=" : "=") . " (" . $text_ids . ");";
            } else {
                $body .= "where id " . ($inverse ? "!=" : "=") . " (" . $ids . ");";
            }
        }
        $body .= "limit 500;";
        $result = 0;

        $gens = $this->IGDB_API_con($url, $body);

        if ($gens) {
            foreach ($gens as $key => $gen) {
                $dades = array(
                    "IGDB_id" => $gen["id"],
                    "name" => $gen["name"],
                );

                $this->delete("genre", array("IGDB_id" => $gen["id"])); //delete to prevent duplicates
                if ($this->insert("genre", $dades)) { //insert platform
                    $result++;
                } else {
                    $result = 0;
                    break;
                }
            }
            return $result;
        } else {
            $this->result["alert"] = 1;
            $this->result["response"] = "No hay nada pendiente";
            return 1;
        }
    }

    public function best_games_of_gen() {
        $id = $this->post_dat["id"];
        $no_ids = $this->post_dat["ids_loaded"];
        
        $html = $this->best_games_of("genres", $id, $no_ids, 0);
        $this->result["html"] = $html;
    }

    /* End API querys */
}

$obj = new platform($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
