<?php
$games = $VGO->select("game g", "g.IGDB_id, g.cover, g.title, g.GameType_IGDB_id", false, array());
print_r($games);
?>
<h2>Juegos</h2>
<div class="row text-center">
    <?php
    foreach ($games as $key => $game) {
        ?>
        <div class="col-2 p-1">
            <div class="border border-3 w-100 p-1" onclick="reload_more({id:<?= $game["IGDB_id"] ?>})">
                <img src="//images.igdb.com/igdb/image/upload/t_cover_med/<?= $game["cover"] ?>.jpg" class="w-100">
                <div><?= $game["title"] ?></div>
            </div>
        </div>
        <?php
    }
    ?>
</div>