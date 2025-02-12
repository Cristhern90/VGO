<?php

$page = "list";
if (isset($_GET["id"])) {
    $page = "element";
}

include $page.'.php';
