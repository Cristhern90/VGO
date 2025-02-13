<?php
$games = $VGO->select("game g", "g.cover, g.title, g.Engine_IGDB_id, g.GameType_IGDB_id",false, array("IGDB_id"=>$id));
print_r($games);
?>