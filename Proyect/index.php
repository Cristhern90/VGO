<?php
session_start();
$user_id = 0;
$page = "login";
if (isset($_COOKIE["user_id"])) {
    $user_id = $_COOKIE["user_id"]; // get user id
    $page = "home";
    if (iseet($_GET["page"])) {
        $page = $_GET["page"]; // get page
    }
}
?>
<html>
    <?php
    include './includes/head.php';
    ?>
    <body>
        <?php
        include './includes/header.php';
        include './front/' . $page . '/index.php';
        include './includes/footer.php';
        ?>
    </body>
</html>