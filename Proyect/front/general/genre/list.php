<?php
$gens = $VGO->select("genre g", "g.*", "", array(), false, "g.name");
?>
<h1>Generos</h1>
<span><button class="btn-secondary" title="Descargar generos" onclick="act_gens()"><i class="fa fa-reload"></i>a</button></span>
<div class="row">
    <?php foreach ($gens as $key => $gen) { ?>
        <div class="col-2 p-1 text-center">
            <div class="border border-3 w-100 p-1" onclick="reload_more({id:<?= $gen["IGDB_id"] ?>})">
                <div><?= $gen["name"] ?></div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    const page = "front/<?= $prepage ?>/<?= $page ?>/ajax/<?= $page ?>.php";

    function act_gens() {
        let myArray = new FormData();
        myArray.append("function", "download_new_gens");
        conect(page, myArray);
    }
</script>