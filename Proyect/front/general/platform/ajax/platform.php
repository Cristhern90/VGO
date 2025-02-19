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

    /* API */

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

    private function act_plat($id = "") {
        $url = "https://api.igdb.com/v4/platforms";
        $body = "fields id, category, generation, name, slug, platform_family.name,versions.name, versions.platform_logo.image_id, versions.platform_version_release_dates.region, versions.platform_version_release_dates.date;";
        if ($id) {
            $body .= "where id = " . $id . ";";
        }
        $body .= "limit 500;";
        $result = 0;

        $plats = $this->IGDB_API_con($url, $body);

        foreach ($plats as $key => $plat) {
            $dades = array(
                "IGDB_id" => $plat["id"],
                "name" => $plat["name"],
                "PlatformType_IGDB_id" => $plat["category"],
            );
            
            if (isset($plat["generation"])) {
                $dades["generation"] = $plat["generation"]; //if has family in api add to insert values
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
    }

    private function if_not_exists_insert_platformFamily($id, $name) {
        $count = $this->select("platformfamily", "count(*) cant", false, array("IGDB_id" => $id))[0]["cant"];
        if (!$count) {
            $this->insert("platformfamily", array("IGDB_id" => $id, "name" => $name));
        }
    }

    /* End API */
}

$obj = new platform($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
