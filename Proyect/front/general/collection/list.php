<?php
$collections = $VGO->select("collection c", "c.*", "", array(), false, "c.name");
?>
<h1>Series</h1>
<div class="row">
    <?php foreach ($collections as $key => $collection) { ?>
        <div class="col-2 p-1 text-center">
            <div class="border border-3 w-100 p-1" onclick="reload_more({id:<?= $collection["IGDB_id"] ?>})">
                <div><?= $collection["name"] ?></div>
            </div>
        </div>
    <?php } ?>
</div>