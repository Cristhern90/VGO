<?php

$general_array = array(
    "collection" => array("collections", "Series", "Serie",0),
    "genre" => array("genres", "Generos", "Genero",0),
    "franchise" => array("franchises", "Franquicias", "Franquicia",0),
    "developer" => array("involved_companies.company", "Desarrolladores", "Desarrollador",1)
);

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
    include $page . '/index.php';
}