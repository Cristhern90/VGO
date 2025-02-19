<?php
$plats = $VGO->select("platform p", "p.*, pf.name family", "LEFT JOIN platformfamily pf ON pf.IGDB_id = p.PlatformFamily_IGDB_id");
?>
<h1>Plataformas</h1>
<span><button class="btn-secondary" title="Actualizar plataformas" onclick="act_plats()"><i class="fa fa-reload"></i>a</button></span>
<div class="row">
    <?php foreach ($plats as $key => $plat) { ?>
        <div class="col-2 p-1">
            <div class="border border-3 w-100 p-1" onclick="reload_more({id:<?= $plat["IGDB_id"] ?>})">
                <div><?= $plat["name"] ?> <?= ($plat["family"] ? "(" . $plat["family"] . ")" : "") ?></div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    const page = "front/<?= $prepage ?>/<?= $page ?>/ajax/<?= $page ?>.php";

    function act_plats() {
        let myArray = new FormData();
        myArray.append("function", "download_new_plats");
        conect(page, myArray);
    }
</script>