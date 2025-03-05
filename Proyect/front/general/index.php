<?php

$general_list = array("collection", "genre");

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