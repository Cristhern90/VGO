<?php

$page = "list";
$id = "";
if (isset($_GET["id"])) {
    $page = "element";
    $id = $_GET["id"];
}

include $page.'.php';
