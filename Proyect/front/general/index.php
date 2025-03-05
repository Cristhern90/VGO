<?php

$general_array = array("collection"=>"collections", "genre"=>"genres");

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