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

    public function download_new_plats() {
        $ids = $this->select("platform", "GROUP_CONCAT(IGDB_id ORDER BY IGDB_id ASC SEPARATOR ', ') ids")[0]["ids"];
        if ($this->act_plat($ids, 1)) {
            $this->result["reload"] = 1; //send reload action
        } else {
            $this->result["errorCode"] = 3;
        }
    }

    public function act_all_plats() {
        if ($this->act_plat()) {
            $this->result["reload"] = 1; //send reload action
        } else {
            $this->result["errorCode"] = 3;
        }
    }

    public function act_specific_plats() {
        if ($this->act_plat($this->op["id"])) {
            $this->result["reload"] = 1; //send reload action
        } else {
            $this->result["errorCode"] = 3;
        }
    }

    /* END AJAX call functions */

    /* SQL querys */

    private function if_not_exists_insert_platformFamily($id, $name) {
        $count = $this->select("platformfamily", "count(*) cant", false, array("IGDB_id" => $id))[0]["cant"];
        if (!$count) {
            $this->insert("platformfamily", array("IGDB_id" => $id, "name" => $name));
        }
    }

    /* END SQL querys */


    /* API querys */

    private function act_plat($ids = "", $inverse = 0) {
        $url = "https://api.igdb.com/v4/platforms";
        $body = "fields id, category, generation, name, slug, platform_family.name,versions.name, versions.platform_logo.image_id, versions.platform_version_release_dates.region, versions.platform_version_release_dates.date;";
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

        $plats = $this->IGDB_API_con($url, $body);

        if ($plats) {
            foreach ($plats as $key => $plat) {
                $logo = "";
                if (isset($plat["versions"])) {
                    foreach ($plat["versions"] as $key => $version) {
                        if ($version["name"] == "Initial version") {
                            if (isset($version["platform_logo"]["image_id"])) {
                                $logo = $version["platform_logo"]["image_id"];
                            }
                        } else {
                            if (!$logo) {
                                if (isset($version["platform_logo"]["image_id"])) {
                                    $logo = $version["platform_logo"]["image_id"];
                                }
                            }
                        }
                    }
                }
                $dades = array(
                    "IGDB_id" => $plat["id"],
                    "name" => $plat["name"],
                    "logo" => $logo
                );

                if (isset($plat["category"])) {
                    $dades["PlatformType_IGDB_id"] = $plat["category"]; //if has category in api add to insert values
                }
                if (isset($plat["generation"])) {
                    $dades["generation"] = $plat["generation"]; //if has generation in api add to insert values
                }
                if (isset($plat["platform_family"])) {
                    $this->if_not_exists_insert_platformFamily($plat["platform_family"]["id"], $plat["platform_family"]["name"]); //add families if not exists
                    $dades["PlatformFamily_IGDB_id"] = $plat["platform_family"]["id"]; //if has family in api add to insert values
                }
                $this->delete("platform", array("IGDB_id" => $plat["id"])); //delete to prevent duplicates
                if ($this->insert("platform", $dades)) { //insert platform
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

    public function best_games_of_plat() {
        $id = $this->post_dat["id"];
        $no_ids = $this->post_dat["ids_loaded"];
        
        $html = $this->best_games_of("platforms", $id, $no_ids, 0);
        $this->result["html"] = $html;
    }

    /* End API querys */
}

$obj = new platform($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
