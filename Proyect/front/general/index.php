<?php

$general_array = array("collection"=>array("collections","Series","Serie"), "genre"=>array("genres","Generos","Genero"), "franchise"=>array("franchises","Franquicias","Franquicia"));


$general_list = array_keys($general_array);

if (in_array($page, $general_list)) {
    $elemnt = "list";
    $id = "";
    if (isset($_GET["id"])) {
        $elemnt = "element";
        $id = $_GET["id"];
    }
    include $elemnt . '.php';
} else {
    include $page.'/index.php';
}