<?php

$elements = $VGO->select($page." p", "p.*", "", array(), false, "p.name");
?>
<h1><?= $general_array[$page][1]?></h1>
<span><button class="btn-secondary" title="Descargar generos" onclick="act_gens()"><i class="fa fa-reload"></i>a</button></span>
<div class="row">
    <?php foreach ($elements as $key => $element) { ?>
        <div class="col-2 p-1 text-center">
            <div class="border border-3 w-100 p-1" onclick="reload_more({id:<?= $element["IGDB_id"] ?>})">
                <div><?= $element["name"] ?></div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    const page = "front/<?= $prepage ?>/ajax/general.php";

    function act_gens() {
        let myArray = new FormData();
        myArray.append("function", "download_new_gens");
        conect(page, myArray);
    }
</script>