<?php

$elemnt = "list";
$id = "";
if (isset($_GET["id"])) {
    $elemnt = "element";
    $id = $_GET["id"];
}

include $elemnt.'.php';
