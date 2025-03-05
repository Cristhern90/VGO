<?php
session_start();

include './config/VGO.php';

$VGO = new VGO();

$user_id = 0;
$page = "home";
$prepage = "general";
if (isset($_COOKIE["user_id"])) {
    $user_id = $_COOKIE["user_id"]; // get user id
    $prepage = "personal";
    if (iseet($_GET["page"])) {
        $page = $_GET["page"]; // get page
    }
} else {
    if (isset($_GET["page"])) {
        $page = $_GET["page"]; // get page
    }
}
?>
<html>
    <?php
    include './includes/head.php';
    ?>
    <body class="container container-fluid">
        <?php
        include './includes/header.php';
//        echo '<hr>' . $page;
        include './front/' .$prepage . "/index.php";
        include './includes/footer.php';
        ?>
    </body>
</html>