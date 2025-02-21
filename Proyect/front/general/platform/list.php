<?php
$plats = $VGO->select("platform p", "p.*, pf.name family, IFNULL(p.PlatformFamily_IGDB_id,0) family_id, IFNULL(p.PlatformType_IGDB_id,0) type_id", 
        "LEFT JOIN platformfamily pf ON pf.IGDB_id = p.PlatformFamily_IGDB_id", array(), false, "generation DESC, family_id, p.name");
?>
<h1>Plataformas</h1>
<span><button class="btn-secondary" title="Actualizar plataformas" onclick="act_plats()"><i class="fa fa-reload"></i>a</button></span>
<div class="row">
    <?php foreach ($plats as $key => $plat) { ?>
        <div class="col-2 p-1 text-center" data-family="<?= $plat["family_id"] ?>" data-type="<?= $plat["type_id"] ?>" data-generation="<?= ($plat["generation"] ? $plat["generation"] : 0) ?>">
            <div class="border border-3 w-100 p-1" onclick="reload_more({id:<?= $plat["IGDB_id"] ?>})">
                <img src="<?= ($plat["logo"] ? "//images.igdb.com/igdb/image/upload/t_thumb/" . $plat["logo"] . ".jpg" : "images/logo.png") ?>" class="w-50" alt=""/>
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