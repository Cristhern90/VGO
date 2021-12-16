<div class="container busquedaAPI">
    <div class="row">
        <h2 class="col-12 col-sm-12">Busqueda API en IGDB:</h2>
        <input name="searchAPI" id="searchAPI" type="text" class="search col-sm-10"  value="<?= (isset($_GET["id"]) ? $_GET["id"] : "") ?>">

        <!--<div class="col-sm-10"><br></div>-->

        <div class="col-sm-2">
            <button id="buscar" class="col-sm-12">Buscar</button>    
            <button id="buscarExact" class="col-sm-12">Buscar por nombre exacto</button>
        </div>

    </div>
    <div id="list_games_api" class="row"></div>
    <script>
        $("#buscar").click(function () {

            $("#loading").show();
            var name = $("#searchAPI").val();
            if (name) {
                var ret = conenct_post('game', 'search_game_by_name', name);
                if (ret.str == 0) {
                    $("#list_games_api").html("No hay juegos que coincidan con la busqueda y que no estén ya registrados");
                } else {
                    $("#list_games_api").html(ret.result);
                }
            } else {
                alert("¿Que es lo que quieres buscar?");
            }
            $("#loading").hide();

        });
        $("#buscarExact").click(function () {

            $("#loading").show();
            var name = $("#searchAPI").val();
            if (name) {
                var ret = conenct_post('game', 'search_game_by_name_exact', name);
                if (ret.str == 0) {
                    $("#list_games_api").html("No hay juegos que coincidan con la busqueda y que no estén ya registrados");
                } else {
                    $("#list_games_api").html(ret.result);
                }
            } else {
                alert("¿Que es lo que quieres buscar?");
            }
            $("#loading").hide();

        });
    </script>
</div>