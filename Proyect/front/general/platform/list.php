<?php
$plats = $VGO->select("platform","*");


print_r($plats);
?>
<h1>Plataformas</h1>
<span><button class="btn-secondary" title="Actualizar plataformas" onclick="act_plats()"><i class="fa fa-reload"></i>a</button></span>
<script>
    const page = "front/<?= $prepage ?>/<?= $page ?>/ajax/<?= $page ?>.php";

    function act_plats() {
        let myArray = new FormData();
        myArray.append("function", "act_plats");
        console.log(page);
        conect(page, myArray);
    }
</script>