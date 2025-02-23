<?php
$games = $VGO->select("game g", "g.*", false, array("IGDB_id" => $id));
echo "<pre>";
print_r($games);
echo "</pre>";
echo "<hr>";

$collections = $VGO->select("collectiongame cg", "cg.*, c.name", "INNER JOIN collection c ON c.IGDB_id = cg.Collection_IGDB_id", array("Game_IGDB_id" => $id));
echo "<pre>";
print_r($collections);
echo "</pre>";
echo "<hr>";
?>

<button class="col-12 col-lg-6" onclick="regist_game(<?= $id ?>, 0)">Actualizar</button>