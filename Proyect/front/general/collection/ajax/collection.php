<?php

include '../../../../config/API.php';

/**
 * Description of platform
 *
 * @author Cristian
 */
class collection extends API {

    #[\Override]
    public function __construct($post_dat) {
        $this->url_json_bbdd = "../../../../config/dades/";
        parent::__construct($post_dat);
    }

    public function best_games_of_gen() {
        $id = $this->post_dat["id"];
        $no_ids = $this->post_dat["ids_loaded"];
        
        $html = $this->best_games_of("collections", $id, $no_ids, 0);
        $this->result["html"] = $html;
    }

    /* End API querys */
}

$obj = new collection($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
