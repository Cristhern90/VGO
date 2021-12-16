<?php
if (isset($_COOKIE["user_id"])) {
    if (!isset($_GET["id"])) {
        include 'perfil/inicio.php';
    } else {
        include 'perfil/' . $_GET["id"] . ".php";
    }
} else {
    ?>
    <script>
        location.href = "<?= $url ?>";
    </script>
<?php } ?>