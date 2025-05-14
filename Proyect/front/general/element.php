<?php
$element = $VGO->select($page . " p", "*", false, array("p.IGDB_id" => $_GET["id"]))[0];
$reg_games = $VGO->select($page . "game pg", "g.IGDB_id, g.title, g.cover", "INNER JOIN game g ON g.IGDB_id = pg.Game_IGDB_id", array("pg." . ucfirst($page) . "_IGDB_id" => $_GET["id"]), "g.IGDB_id, g.title, g.cover,g.first_release_date", "g.first_release_date ASC");
?>
<h1><?=$general_array[$page][2]?>: <?= $element["name"] ?></h1>
<hr>
<div class="">
    <h2>Juegos registrados:</h2>
    <div class="row" id="registrados">
        <?php foreach ($reg_games as $key => $reg_game) { ?>
            <div class="col-4 col-md-3 col-xl-2 p-1 game_element" data-id="<?= $reg_game["IGDB_id"] ?>">
                <div class="border border-3 w-100 p-1">
                    <img src="//images.igdb.com/igdb/image/upload/t_cover_med/<?= $reg_game["cover"] ?>.jpg" class="w-100">
                    <div><?= $reg_game["title"] ?></div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<hr>
<div class="mb-3">
    <h2>Juegos no registrados:</h2>
    <div class="row" id="no_registrados">

    </div>
    <button class="w-100" onclick="get_games()">Cargar más</button>
</div>
<script>
    const page = "front/<?= $prepage ?>/ajax/general.php";

    function get_games() {
        let myArray = new FormData();
        myArray.append("function", "best_games");
        myArray.append("id", <?= $_GET["id"] ?>);
        myArray.append("type", "<?= $general_array[$page][0] ?>");
        myArray.append("excusive", <?= $general_array[$page][3] ?>);
        let ids_loaded = "";
        let count = 0;
        $(".game_element").each(function (index, value) {
            ids_loaded += (count ? "," : "") + $(this).data("id");
            count++;
        });
        console.log(ids_loaded);

        myArray.append("ids_loaded", ids_loaded);
        res = conect(page, myArray);
        $("#no_registrados").append(res.html);
    }

</script>