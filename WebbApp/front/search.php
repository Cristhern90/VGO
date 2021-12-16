<div class="container busquedaDDBB">
    <div class="row">
        <h2 class="col-6 col-sm-6">Busqueda en VGO:</h2>
        <div class="col-6 col-sm-6" style="margin-bottom: 25px;font-size: 2rem;text-align: right">
            <a href="<?= $url ?>/searchAPI">"API IGDB"</a>
        </div>
        <input name="searchBBDD" id="searchBBDD" type="text" class="search col-sm-10" value="<?= (isset($_GET["id"]) ? $_GET["id"] : "") ?>">
        <button id="buscar" class="col-sm-2">Buscar</button>
    </div>

    <div id="list_games_bbdd" class="row">
        <p class="nada">No hay datos</p>
    </div>
    <div id="To_search_API" class="row">
        <p class="nada">Si el juego que buscas no aparece aquí debés añadirlo desde <a href="<?= $url ?>/searchAPI">"Busqueda API en IGDB"</a></p>
    </div>
    <script>
        $("#buscar").click(function () {
            var name = $("#searchBBDD").val();
            if (name) {
                search(name);
            } else {
                alert("¿Que es lo que quieres buscar?");
            }

        });
<?php if (isset($_GET["id2"]) && $_GET["id2"]) { ?>
            setTimeout(function () {
                $("#buscar").click();
                //do something special
            }, 500);
<?php } ?>

        function search(name) {
            var ret = conenct_post('game', 'search_game_by_name_in_bbdd', name);
            if (ret.str == 0) {
                $("#list_games_bbdd").html("No hay juegos que coincidan con la busqueda y que no estén ya registrados");
            } else {
                $("#list_games_bbdd").html(ret.result);
            }
        }


    </script>
</div>