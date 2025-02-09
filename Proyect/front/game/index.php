<?php
$games = $VGO->select("game");

echo "<hr>";

echo '<pre>';
print_r($games);
echo '</pre>';

echo "<hr>";

foreach ($games as $key => $game) {
    ?>
    <img src="//images.igdb.com/igdb/image/upload/t_thumb/<?= $game["cover"] ?>">
    <?php
}
    