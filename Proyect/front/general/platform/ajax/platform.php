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

    public function act_plats() {
        $url = "https://api.igdb.com/v4/platforms";
        $body = "fields id, category, generation, name, slug, platform_family.name,
            versions.name, versions.platform_logo.image_id, versions.platform_version_release_dates.region, versions.platform_version_release_dates.date;
            where id = 508;
            limit 500;";

        $plats = $this->IGDB_API_con($url, $body);

//        print_r($plats);

        foreach ($plats as $key => $plat) {
//            echo $key. " => ".$plat["id"]."<br>";
            if (isset($plat["platform_family"])) {
                $this->if_not_exists_insert_platformFamily($plat["platform_family"]["id"], $plat["platform_family"]["name"]); //add families if not exists
            }
//            $this->delete("platform", array("IGDB_id"=>$plat["id"]));//delete to prevent duplicates
//            $dades = array(
//                "IGDB_id"=>$plat["id"],
//                "name"=>$plat["name"],
//                "generation"=>$plat["generation"],
//                "PlatformType_IGDB_id"=>$plat["category"],
//                "PlatformFamily_IGDB_id"=>$plat["platform_family"]["id"],
//            );
//            $this->insert("platform", $dades);//insert platform
        }

        $this->result["reload"] = 1; //send reload action
    }

    private function if_not_exists_insert_platformFamily($id, $name) {
        $count = $this->select("platformfamily", "count(*) cant", false, array("IGDB_id" => $id));
        print_r($count);
//        if(!$count){
//            $this->insert("platformfamily", array("IGDB_id"=>$id,"name"=>$name));
//        }
    }

    /* End API */
}

$obj = new platform($_POST);
//print_r($obj->post_dat);
$function = $obj->post_dat["function"];
$obj->$function();
echo json_encode($obj->result, JSON_PRETTY_PRINT);
