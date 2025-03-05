<?php

include '../../../config/API.php';

/**
 * Description of platform
 *
 * @author Cristian
 */
class general extends API {

    #[\Override]
    public function __construct($post_dat) {
        $this->url_json_bbdd = "../../../config/dades/";
        parent::__construct($post_dat);
    }

    public function best_games() {
        $id = $this->post_dat["id"];
        $type = $this->post_dat["type"];
        $no_ids = $this->post_dat["ids_loaded"];
        $excusive = $this->post_dat["excusive"];

        $html = $this->best_games_of($type, $id, $no_ids, $excusive);
        $this->result["html"] = $html;
    }

    /* End API querys */
}

$obj = new general($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
