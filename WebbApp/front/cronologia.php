<h2>Cronología</h2>
<?php if ($user->editor) { ?>
    <button class="rounded-3 cyan_but" onclick="$('#new_group').show()">Nuevo Grupo</button>
    <button class="rounded-3 cyan_but" onclick="$('#new_serie').show()">Asignar Serie</button>
    <p>Series: <?php
        $sql_s_0 = "SELECT *
                    FROM cronologiaserie cs
                    INNER JOIN serie s ON s.idSerie = cs.idSerie
                    WHERE cs.idCronologia = " . $_GET["id"];

        $query_s_0 = get_query($sql_s_0);
        $count = 0;
        while ($row_s_0 = $query_s_0->fetch_assoc()) {
            if ($count) {
                echo ", ";
            }
            echo $row_s_0["name"];
            $count++;
        }
        ?></p>
<?php } ?>
<div class="row" style="margin-top: 2em;margin-bottom: 2em;">
    <?php
    $sql_g = "SELECT idGrupoCronologia, name, (SELECT count(*) FROM grupocronologia WHERE idCronologia = " . $_GET["id"] . " Order by orden) num FROM grupocronologia WHERE idCronologia = " . $_GET["id"] . " Order by orden";
    $query_g = get_query($sql_g);
    while ($row_g = $query_g->fetch_assoc()) {
        ?>
        <div class="col">
            <div class="border border-2 border-dark rondo" style="padding: 2em;">
                <h3><?= $row_g["name"] ?></h3>
                <?php if ($user->editor) {
                    ?>
                    <button class="rounded-3 cyan_but" onclick="line_inster(<?= $row_g["idGrupoCronologia"] ?>)">+ <i class="fa fa-arrows-alt-v"></i></button>
                    <button class="rounded-3 cyan_but"  onclick="link_game(0,<?= $row_g["idGrupoCronologia"] ?>)">+ <i class="fa fa-gamepad"></i></button>
                <?php } ?>
                <?php
                $sql_l = "SELECT * FROM lineacronologia WHERE idGrupoCronologia = " . $row_g["idGrupoCronologia"] . " AND altura IS NULL Order by orden";
                $query_l = get_query($sql_l);
                while ($row_l = $query_l->fetch_assoc()) {
                    ?>
                    <h4><?= $row_l["titulo"] ?></h4>
                    <?php if ($user->editor) {
                        ?>
                        <button class="rounded-3 cyan_but" onclick="link_game(<?= $row_l["idLineaCronologia"] ?>,<?= $row_g["idGrupoCronologia"] ?>)">+ <i class="fa fa-gamepad"></i></button>
                        <?php
                    }
                    $sql_j = "SELECT jc.posicion, GROUP_CONCAT(CONCAT(j.name,'|',CASE 
                                    WHEN jc.observacion IS NULL THEN ''
                                    ELSE jc.observacion
                                END ,'|', mj.url,'|', jc.idJuegoCronologia)) games, (
                                    SELECT jc2.posicion 
                                    FROM juegocronologia jc2 
                                    WHERE jc2.idCronologia = jc.idCronologia AND jc2.idLineaCronologia = jc.idLineaCronologia AND jc2.idGrupoCronologia = jc.idGrupoCronologia
                                    order by jc2.posicion DESC LIMIT 1
                                ) count  
                                FROM juegocronologia jc
                                INNER JOIN juego j ON j.idjuego = jc.idJuego
                                LEFT JOIN mediajuego mj ON mj.idJuego = j.idJuego AND mj.tipo = 'cover'
                                WHERE idCronologia = " . $_GET["id"] . " AND idLineaCronologia = " . $row_l["idLineaCronologia"] . " AND idGrupoCronologia = " . $row_g["idGrupoCronologia"] . "
                                GROUP BY posicion 
                                ORDER BY posicion";
//                    echo $sql_j;
                    $query_j = get_query($sql_j);
                    $count = 0;
                    while ($row_j = $query_j->fetch_assoc()) {
                        $games_arr = explode(",", $row_j["games"]);
                        ?>
                        <div class="text-center" style="width: 100%;">
                            <div class="row">
                                <div class="col-11 offset-1">
                                    <button class="rounded-3 cyan_but" onclick="link_game(<?= $row_l["idLineaCronologia"] ?>,<?= $row_g["idGrupoCronologia"] ?>, <?= $row_j["posicion"] ?>)">
                                        <i class="fa fa-long-arrow-alt-up"></i> <i class="fa fa-gamepad"></i>
                                    </button>
                                    <?php if ($count) { ?>
                                        <i class="fas fa-2x fa-long-arrow-alt-down"></i>
                                    <?php } ?>
                                </div>
                                <div class="col-1"><?= $row_j["posicion"] ?></div>
                                <div class="col-11">
                                    <div class="row">
                                        <?php
                                        $desp = (sizeof($games_arr)>1?0:1);
                                        foreach ($games_arr as $key => $game) {
                                            $game_arr = explode("|", $game);
                                            ?>
                                            <div class="col">
                                                <img src="<?= $game_arr[2] ?>" style="width: 100%;max-width: 400px;">
                                                <div>
                                                    <?= $game_arr[0] ?> <?= (isset($game_arr[1]) ? $game_arr[1] : "") ?>
                                                    <button class="rounded-3 cyan_but" onclick="del_game(<?=$game_arr[3]?>,<?= $row_j["posicion"] ?>,<?=$row_g["idGrupoCronologia"]?>,<?=$row_l["idLineaCronologia"]?>,<?=$desp?>)">
                                                        <i class="fa fa-<?=($desp?"unlink":"trash")?>"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php if ($count == $row_j["count"] - 1) { ?>
                                    <div class="col-11 offset-1">
                                        <button class="rounded-3 cyan_but" onclick="link_game(<?= $row_l["idLineaCronologia"] ?>,<?= $row_g["idGrupoCronologia"] ?>, <?= $row_j["posicion"] + 1 ?>)">
                                            <i class="fa fa-long-arrow-alt-down"></i> <i class="fa fa-gamepad"></i>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                        $count++;
                    }
                    ?>
                <?php } ?>
                <div class="text-center" style="width: 100%;margin-top: 2em">
                    <div class="row">
                        <?php
                        $sql_j_n = "SELECT * 
                                FROM juegocronologia jc
                                INNER JOIN juego j ON j.idjuego = jc.idJuego
                                LEFT JOIN mediajuego mj ON mj.idJuego = j.idJuego AND mj.tipo = 'cover'
                                WHERE idCronologia = " . $_GET["id"] . " AND idLineaCronologia IS NULL AND idGrupoCronologia = " . $row_g["idGrupoCronologia"] . "";
//                echo $sql_j_n;
                        $query_j_n = get_query($sql_j_n);
                        while ($row_j_n = $query_j_n->fetch_assoc()) {
                            $games_arr = $row_j_n;
                            ?>
                            <div class="col">
                                <img src="<?= $row_j_n["url"] ?>" style="width: 100%;max-width: 400px;">
                                <div>
                                    <?= $row_j_n["name"] ?> <?= $row_j_n["observacion"] ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php if ($user->editor) { ?>
    <div class="oscur" id="new_group">
        <div class="white">
            <button onclick="$('.oscur').hide()" class="close rondo2 cyan_but">
                <i class="fa fa-times"></i>
            </button>
            <form id="nuevo_grupo">
                <input type="hidden" name="funcion" value="nuevo_grupo">
                <input type="hidden" name="idCronologia" value="<?= $_GET["id"] ?>">
                <h3>Nuevo Grupo</h3>
                <div>Nombre<span style="color: red;">*</span>: 
                    <input type="text" autocomplete="off" required="" name="nombre"> 
                </div>
                <div>Orden<span style="color: red;">*</span>: 
                    <input type="number" autocomplete="off" required="" name="orden"> 
                </div>
                <input type="submit">
            </form>
        </div>
    </div>

    <div class="oscur" id="new_serie">
        <div class="white">
            <button onclick="$('.oscur').hide()" class="close rondo2 cyan_but">
                <i class="fa fa-times"></i>
            </button>
            <form id="add_serie">
                <input type="hidden" name="funcion" value="add_serie">
                <input type="hidden" name="idCronologia" value="<?= $_GET["id"] ?>">
                <h3>Asignar Serie</h3>
                <div>Serie<span style="color: red;">*</span>: 
                    <select name="idSerie" required="">
                        <option value="">Selecciona una serie</option>
                        <?php
                        $sql_s = "SELECT s.idSerie, s.name  
                                    FROM Serie s
                                    LEFT JOIN cronologiaserie cs ON cs.idSerie = s.idSerie AND cs.idCronologia = " . $_GET["id"] . "
                                    WHERE cs.idCronologiaSerie IS NULL 
                                    order by s.name";
                        $query_s = get_query($sql_s);
                        while ($row_s = $query_s->fetch_assoc()) {
                            ?>
                            <option value="<?= $row_s["idSerie"] ?>"><?= $row_s["name"] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <input type="submit">
            </form>
        </div>
    </div>

    <div class="oscur" id="add_line_to_group">
        <div class="white">
            <button onclick="$('.oscur').hide()" class="close rondo2 cyan_but">
                <i class="fa fa-times"></i>
            </button>
            <form id="nueva_linea">
                <input type="hidden" name="funcion" value="nueva_linea">
                <input type="hidden" name="idGrupoCronologia" value="" id="idGrupoCronologia">
                <h3>Nueva linea</h3>
                <div>Título<span style="color: red;">*</span>: 
                    <input type="text" autocomplete="off" required="" name="nombre"> 
                </div>
                <div>Orden<span style="color: red;">*</span>: 
                    <input type="number" autocomplete="off" required="" name="orden"> 
                </div>
                <input type="submit">
            </form>
        </div>
    </div>

    <div class="oscur" id="add_game">
        <div class="white">
            <button onclick="$('.oscur').hide()" class="close rondo2 cyan_but">
                <i class="fa fa-times"></i>
            </button>
            <form id="add_juego">
                <input type="hidden" name="funcion" value="add_juego">
                <input type="hidden" name="idCronologia" value="<?= $_GET["id"] ?>">
                <input type="hidden" name="idGrupoCronologia" value="" id="idGrupoCronologia2">
                <input type="hidden" name="idLineaCronologia" value="" id="idLineaCronologia">
                <input type="hidden" name="desp" value="" id="desp" value="0">
                <h3>Nuevo juego</h3>
                <div>Juego<span style="color: red;">*</span>: 
                    <select required="" name="idJuego">
                        <option value="">Selecciona un juego</option>
                        <?php
                        $sql_jc = "SELECT * 
                                    FROM juego j
                                    INNER JOIN juegoserie js ON js.idJuego = j.idJuego
                                    INNER JOIN cronologiaserie cs ON cs.idSerie = js.idSerie
                                    WHERE cs.idCronologia = " . $_GET["id"] . " ORDER BY name";
                        $query_jc = get_query($sql_jc);
                        while ($row_jc = $query_jc->fetch_assoc()) {
                            ?>
                            <option value="<?= $row_jc["idJuego"] ?>"><?= $row_jc["name"] ?></option>
                        <?php } ?>
                        ?>
                    </select>
                </div>
                <div>Posición<span style="color: red;">*</span>: 
                    <input type="number" autocomplete="off" required="" name="posicion" id="posicion"> 
                </div>
                <div>Observación: 
                    <input type="text" autocomplete="off"name="observacion"> 
                </div>
                <input type="submit">
            </form>
        </div>
    </div>

    <script>
        $("#nuevo_grupo").submit(function (e) {
            var myArray = new FormData($(this)[0]);
            conenction_post("cronologias", myArray);
            e.preventDefault();
        });
        $("#add_serie").submit(function (e) {
            var myArray = new FormData($(this)[0]);
            conenction_post("cronologias", myArray);
            e.preventDefault();
        });
        $("#nueva_linea").submit(function (e) {
            var myArray = new FormData($(this)[0]);
            conenction_post("cronologias", myArray);
            e.preventDefault();
        });
        $("#add_juego").submit(function (e) {
            var myArray = new FormData($(this)[0]);
            conenction_post("cronologias", myArray);
            e.preventDefault();
        });

        function line_inster(grupo) {
            $("#idGrupoCronologia").val(grupo);
            $("#add_line_to_group").show();
        }

        function link_game(linea, grupo, pos = 0) {
            if (pos) {
                $("#posicion").val(pos);
                $("#desp").val(1);
            }
            $("#idGrupoCronologia2").val(grupo);
            $("#idLineaCronologia").val(linea);
            $("#add_game").show();
        }

        function del_game(idJuegoCronologia, posicion, idGrupoCronologia, idLineaCronologia, desp = 0) {
            var myArray = new FormData();
            myArray.append("funcion", "del_juego");
            myArray.append("idJuegoCronologia", idJuegoCronologia);
            myArray.append("posicion", posicion);
            myArray.append("idGrupoCronologia", idGrupoCronologia);
            myArray.append("idLineaCronologia", idLineaCronologia);
            myArray.append("idCronologia",<?= $_GET["id"] ?>);
            myArray.append("desp",desp);
            conenction_post("cronologias", myArray);
        }
    </script>
<?php } ?>