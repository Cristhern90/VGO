<?php

if (!isset($_COOKIE[$session_mod . "order"])) {
    if ($_GET["page"] == "perfil" AND $_GET["id"] == "mis_juegos") {
        $order = "j.name";
    } else {
        $order = "name";
    }
} else {
    $order = $_COOKIE[$session_mod . "order"];
}
$order2 = "";
if (!isset($_COOKIE[$session_mod . "order2"])) {
    $order2 = "ASC";
} else {
    $order2 = $_COOKIE[$session_mod . "order2"];
}
$page = "";
if (!isset($_COOKIE[$session_mod . "page"])) {
    $page = 1;
} else {
    $page = $_COOKIE[$session_mod . "page"];
}

$limit = "";
if ($page == 1) {
    if ($order == "name" || $order == "j.name") {
        if ($order2 == "ASC") {
            $limit = "";
        } else {
            $limit = "ZZZZZZZZZZ";
        }
    } else {
        if ($order2 == "ASC") {
            $limit = "1900-01-01";
        } else {
            $limit = "2900-01-01";
        }
    }
} else {
    $limit = $_COOKIE[$session_mod . "limit"];
}
//echo $limit;