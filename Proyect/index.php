<?php
session_start();
$user_id = 0;
if (isset($_COOKIE["user_id"])) {
    $user_id = $_COOKIE["user_id"]; // get user id
}
?>
<html>
    <?php
    include './includes/head.php';
    ?>
    <body>
        <?php
        include './includes/header.php';
        include './includes/footer.php';
        ?>
    </body>
</html>