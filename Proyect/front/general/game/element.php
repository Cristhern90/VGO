<?php
$game = $VGO->select("game g", "g.*", false, array("IGDB_id" => $id))[0];

$collections = $VGO->select("collectiongame cg", "cg.*, c.name", "INNER JOIN collection c ON c.IGDB_id = cg.Collection_IGDB_id", array("Game_IGDB_id" => $id));
?>
<div class="row">
    <div class="col-9">
        <h1><?= $game["title"] ?></h1>
        <h2>Datos:</h2>
        <ul>
            <li>Fecha de estreno: <?= $game["first_release_date"] ?></li>
            <li>Series:
                <ul>
                    <?php foreach ($collections as $key => $collection) { ?>
                        <li><?= $collection["name"] ?></li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
    </div>
    <div class="col-3 text-end">
        <img src="<?= ($game["cover"] ? "//images.igdb.com/igdb/image/upload/t_cover_med/" . $game["cover"] . ".jpg" : "images/logo.png") ?>" class="w-50" alt=""/>
    </div>
</div>

<button class="col-12 col-lg-4" onclick="regist_game(<?= $id ?>, 0)">Actualizar</button>
<?php
echo "<pre>";
print_r($game);
echo "</pre>";
echo "<hr>";

echo "<pre>";
print_r($collections);
echo "</pre>";
echo "<hr>";
