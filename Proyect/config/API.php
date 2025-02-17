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
include './config/AJAX.php';

class API extends AJAX {

    protected $data_api = array("client" => "", "client_secret" => "", "token" => "");

    #[\Override]
    public function __construct() {
        parent::__construct();
        $this->read_API_Json("./config/dades/API.json");//read JSON of API
    }

    private function read_API_Json($fileName) {
        $json_file = file_get_contents($fileName);
        $json_array = json_decode($json_file, true);

        foreach ($json_array as $key => $json) {
            $this->data_api[$key] = $json;
        }
    }

    private function IGDB_API_con($url, $body) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,CURLOPT_ENCODING => '',CURLOPT_MAXREDIRS => 10,CURLOPT_TIMEOUT => 0,CURLOPT_FOLLOWLOCATION => true,CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

        print_r($response);

        return $response;
    }

    public function save_plats() {
        $url = "https://api.igdb.com/v4/platforms";
        $body = "fields id, category, generation, name, slug, platform_family.name,
            versions.name, versions.platform_logo.image_id, versions.platform_version_release_dates.region, versions.platform_version_release_dates.date;
            limit 500;";
        
        $plats = $this->IGDB_API_con($url, $body);
        
        foreach ($plats as $key => $plat) {
            $this->if_not_exists_insert_platformFamily($plat["platform_family.id"], $plat["platform_family.name"]);//add families if not exists
            $this->delete("platform", array("IGDB_id"=>$plat["id"]));//delete to prevent duplicates
            $dades = array(
                "IGDB_id"=>$plat["id"],
                "name"=>$plat["name"],
                "generation"=>$plat["generation"],
                "PlatformType_IGDB_id"=>$plat["category"],
                "PlatformFamily_IGDB_id"=>$plat["platform_family.id"],
            );
            $this->insert("platform", $dades);//insert platform
        }
    }
    
    private function if_not_exists_insert_platformFamily($id,$name){
        $count = $this->select("platformfamily","count(*) cant",false,array("IGDB_id"=>$id))[0]["cant"];
        if($count){
            $this->insert("platformfamily", array("IGDB_id"=>$id,"name"=>$name));
        }
    }
}
