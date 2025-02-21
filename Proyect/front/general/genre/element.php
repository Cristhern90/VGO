<?php
$gen = $VGO->select("genre g", "*", false,array("g.IGDB_id" => $_GET["id"]))[0];
?>
<h1><?= $gen["name"] ?></h1>
<hr>
<div class="">
    <h2>Juegos registrados:</h2>
</div>
<hr>
<div class="mb-3">
    <h2>Juegos no registrados:</h2>
    <div class="row" id="no_registrados">

    </div>
    <button class="w-100" onclick="get_games()">Cargar más</button>
</div>
<script>
    const page = "front/<?= $prepage ?>/<?= $page ?>/ajax/<?= $page ?>.php";

    function get_games() {
        let myArray = new FormData();
        myArray.append("function", "best_games_of_gen");
        myArray.append("id", <?= $_GET["id"] ?>);
        //get loaded games ids
        let ids_loaded = "";
        let count = 0;
        $(".game_element").each(function(index,value){
            ids_loaded += (count?",":"")+$(this).data("id");
            count++;
        });
        console.log(ids_loaded);
        
        myArray.append("ids_loaded", ids_loaded);
        res = conect(page, myArray);
        $("#no_registrados").append(res.html);
    }
    
</script>