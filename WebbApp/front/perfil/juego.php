<?php
if (!isset($_COOKIE["juego_desp"])) {
    $desp = 7;
} else {
    $desp = $_COOKIE["juego_desp"];
}
$sql = "SELECT j.*, mj.url, s.name serie, p.name plat_name, p.logo plat_logo, p.local, jp.fechaEstreno, d.name vend_name, d.logo vend_logo, d.caduca, ujp.FechaAdquisicion, ujp.coste, ujp.caducado, (
            SELECT GROUP_CONCAT(CONCAT(name,'|',logo) SEPARATOR ',') 
            FROM compania c
            INNER JOIN juegocompania jc ON jc.idCompania = c.idCompania
            WHERE jc.idJuego = j.idjuego
        ) desarrolladoras, (
            SELECT GROUP_CONCAT(mj2.url) 
            FROM mediajuego mj2
            WHERE mj2.idJuego = j.idJuego AND mj2.tipo != 'cover' AND mj2.tipo != 'video'
            ORDER BY mj2.tipo
        ) imagenes
        FROM usuariojuegoplataforma ujp
        INNER JOIN juegoplataforma jp ON jp.idJuegoPlataforma = ujp.idJuegoPlataforma
        INNER JOIN juego j ON j.idJuego = jp.idJuego
        LEFT JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
        LEFT JOIN juegoserie sj ON sj.idJuego = j.idJuego 
        LEFT JOIN serie s ON s.idSerie = sj.idSerie
        INNER JOIN plataforma p ON p.idPlataforma = jp.idPlataforma 
        LEFT JOIN distribuidor d ON d.idDistribuidor = ujp.idDistribuidor
        WHERE ujp.idUsuarioJuegoPlataforma = " . $_GET["id2"];

$query = get_query($sql);

$row = $query->fetch_assoc();

$imgs = explode(",", $row["imagenes"]);

$rand = rand(0, sizeof($imgs) - 1);

$img = $imgs[$rand];
?>
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-12 col-lg-9">
        <h1><?= $row["name"] ?> 
            <span style="font-size: 0.5em;position: relative;top: -0.25em;">
                <?php if ($row["caducado"] != 1) { ?>
                    <?php
                    $sql_tj = "SELECT * FROM tiempojuego WHERE idUsuarioJuegoPlataforma = " . $_GET["id2"] . " AND idPartida IS NULL AND fechaFin IS NULL";
                    $query_tj = get_query($sql_tj);
                    $count = 0;
                    while ($row_tj = $query_tj->fetch_assoc()) {
                        $count++;
                        ?>
                        <button class="rondo2 cyan_but" title="Iniciar juego sin partida" onclick="conenct_post_arr('game', 'parar2', ['NULL',<?= $_GET["id2"] ?>])"><i class="fa fa-stop"></i></button>
                    <?php } ?>
                    <?php if ($count == 0) { ?>
                        <button class="rondo2 cyan_but" title="Iniciar juego sin partida" onclick="conenct_post_arr('game', 'jugar', ['NULL',<?= $_GET["id2"] ?>])"><i class="fa fa-play"></i></button>
                    <?php } ?>
                    <?php if ($row["caduca"] == 1) { ?>
                        <button class="rondo2 cyan_but" style="float:right" title="Caducar" onclick="conenct_post_arr('game', 'caduca', [<?= $_GET["id2"] ?>])"><i class="fa fa-times"></i></button>
                    <?php } ?>
                <?php } else { ?>
                    <?php if ($row["caduca"] == 1) { ?>
                        <button class="rondo2 cyan_but" style="float:right" title="Renovar" onclick="conenct_post_arr('game', 'renueva', [<?= $_GET["id2"] ?>])"><i class="fa fa-redo"></i></button>
                    <?php } ?>
                <?php } ?>
            </span>
        </h1>
        <ul style="list-style: none; padding: 0;">
            <li style="font-weight: 600;">Datos generales del juego: </li>
            <li>Primera fecha de estreno: <?= $row["PrimeraFechaEstreno"] ?></li>
            <li>Serie: <?= $row["serie"] ?></li>
            <li>Desarrolladores: <?php
                $arr_des = explode(",", $row["desarrolladoras"]);
                $count = 0;
                foreach ($arr_des as $key => $des) {
                    if ($count) {
                        echo ', ';
                    }
                    $arr_de = explode("|", $des);
                    if (isset($arr_de[1])) {
                        ?>
                        <?= $arr_de[0] ?> <img src="<?= $arr_de[1] ?>" style="width: 2em;">
                        <?php
                    } else {
                        echo $arr_de[0];
                    }
                    $count++;
                }
                ?></li>
            <br>
            <li style="font-weight: 600;">Datos especificos del juego: </li>
            <li>Plataforma: <?= $row["plat_name"] ?> <img src="<?= ($row["local"] ? $url : "") . $row["plat_logo"] ?>" style="width: 2em;"></li>
            <li>Estreno en <?= $row["plat_name"] ?>: <?= $row["fechaEstreno"] ?></li>
            <li>Vendedor: <?= $row["vend_name"] ?> <img src="<?= $url ?>/imagenes/dist_logos/<?= $row["vend_logo"] ?>" style="width: 2em;"></li>
            <li>Fecha compra: <?= $row["FechaAdquisicion"] ?></li>
            <li>Coste: <?= $row["coste"] ?>€</li>
            <li>Partidas: 
                <button class="cyan_but rondo2" onclick="$('#new_partida').show();">
                    <i class="fa fa-plus"></i>
                </button>
                <table class="datatable w-100 text-center">
                    <thead>
                        <tr>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Total Horas</th>
                            <th><span class="observacion-th"></span></th>

                            <?php if ($row["caducado"] != 1) { ?>
                                <th><span class="opciones-th"></span></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_partidas = "
                            SELECT p.*, (
                                SELECT SUM(TIMESTAMPDIFF(SECOND, tj.fechaInicio, tj.fechaFin)) as dif  
                                FROM tiempojuego tj
                                WHERE tj.idPartida = p.idPartida
                                GROUP BY tj.idPartida
                            ) horas 
                            FROM partida p 
                            WHERE p.idUsuarioJuegoPlataforma = " . $_GET["id2"];
                        $query_partidas = get_query($sql_partidas);
                        while ($row_partidas = $query_partidas->fetch_assoc()) {
                            $horas = $row_partidas["horas"] / 60 / 60;
                            $horas_int = (int) $horas;
                            $minutos = $row_partidas["horas"] / 60 - $horas_int * 60;
                            $minutos_int = (int) $minutos;
                            $segundos = $row_partidas["horas"] - $horas_int * 60 * 60 - $minutos_int * 60;
                            ?>
                            <tr>
                                <td><?= $row_partidas["fechaInicio"] ?></td>
                                <td class="fin_<?= $row_partidas["idPartida"] ?>">
                                    <span class="show"><?= $row_partidas["fechaFin"] ?></span>
                                    <?php if ($row["caducado"] != 1) { ?>
                                        <input class="hide datepicker" value="<?= $row_partidas["fechaFin"] ?>" id="input_<?= $row_partidas["idPartida"] ?>">
                                        <button onclick="$('.fin_<?= $row_partidas["idPartida"] ?> .show').hide(); $('.fin_<?= $row_partidas["idPartida"] ?> .hide').show();" class="rondo2 cyan_but show" title="Terminar juego"><i class="fa fa-edit"></i></button>
                                        <button class="rondo2 cyan_but hide" title="Terminar juego" onclick="conenct_post_arr('game', 'fin_partida', [<?= $row_partidas["idPartida"] ?>, $('#input_<?= $row_partidas["idPartida"] ?>').val()])"><i class="fa fa-save"></i></button>
                                        <button onclick="$('.fin_<?= $row_partidas["idPartida"] ?> .show').show(); $('.fin_<?= $row_partidas["idPartida"] ?> .hide').hide();" class="rondo2 cyan_but hide" title="Terminar juego"><i class="fa fa-times"></i></button></td>
                                <?php } ?>
                                <td><?= $horas_int ?>h:<?= $minutos_int ?>m:<?= $segundos ?>s</td>

                                <td><?= $row_partidas["observacion"] ?></td>
                                <?php if ($row["caducado"] != 1) { ?>
                                    <td>
                                        <?php
                                        $sql_tj = "SELECT * FROM tiempojuego WHERE idUsuarioJuegoPlataforma = " . $_GET["id2"] . " AND idPartida = " . $row_partidas["idPartida"] . " AND fechaFin IS NULL";
                                        $query_tj = get_query($sql_tj);
                                        $count = 0;
                                        while ($row_tj = $query_tj->fetch_assoc()) {
                                            $count++;
                                            ?>
                                            <button class="rondo2 cyan_but" title="Terminar juego" onclick="conenct_post_arr('game', 'parar', [<?= $row_partidas["idPartida"] ?>,<?= $_GET["id2"] ?>,<?= $row_tj["idTiempoJuego"] ?>])"><i class="fa fa-stop"></i></button>
                                        <?php } ?>
                                        <?php if ($count == 0) { ?>
                                            <button class="rondo2 cyan_but" title="Iniciar juego" onclick="conenct_post_arr('game', 'jugar', [<?= $row_partidas["idPartida"] ?>,<?= $_GET["id2"] ?>])"><i class="fa fa-play"></i></button>
                                        <?php } ?>
                                        <button class="rondo2 cyan_but" title="registar horas" onclick="add_time(<?= $row_partidas["idPartida"] ?>)"><i class="fa fa-calendar"></i></button>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        <?php
                        $sql_no_partida = "SELECT tj.fechaInicio, (
                                                    SELECT SUM(TIMESTAMPDIFF(SECOND, tj2.fechaInicio, tj2.fechaFin)) as dif
                                                    FROM tiempojuego tj2
                                                    WHERE tj2.idUsuarioJuegoPlataforma = tj.idUsuarioJuegoPlataforma AND tj2.idPartida IS NULL
                                                ) horas 
                                                FROM tiempojuego tj 
                                                WHERE tj.idUsuarioJuegoPlataforma = " . $_GET["id2"] . " AND tj.idPartida IS NULL
                                                ORDER BY tj.fechaInicio DESC LIMIT 1";
//                        echo $sql_no_partida;
                        $query_no_partida = get_query($sql_no_partida);
                        while ($row_no_partida = $query_no_partida->fetch_assoc()) {
                            $horas = $row_no_partida["horas"] / 60 / 60;
                            $horas_int = (int) $horas;
                            $minutos = $row_no_partida["horas"] / 60 - $horas_int * 60;
                            $minutos_int = (int) $minutos;
                            $segundos = $row_no_partida["horas"] - $horas_int * 60 * 60 - $minutos_int * 60;
                            ?>
                            <tr>
                                <td><?= explode(" ", $row_no_partida["fechaInicio"])[0] ?></td>
                                <td>-</td>
                                <td><?= $horas_int ?>h: <?= $minutos_int ?>m: <?= $segundos ?>s</td>
                                <td>Sin partida</td>
                                <td>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </li>
        </ul>
    </div>
    <div class="col-md-12 col-lg-3" style="padding-bottom: 25px;">
        <img src="<?= $row["url"] ?>" class="w-100" alt="...">
    </div>
    <?php
    if (!isset($_COOKIE["lunes"])) {
        $lunes = date('Y-m-d', strtotime('monday this week'));
    } else {
        $lunes = $_COOKIE["lunes"];
//        echo $_COOKIE["lunes"];
    }
    ?>
    <div class="col-sm-12">
        <button class="rondo cyan_but" onclick="conenct_post_arr('game', 'del_session', ['lunes'])">Hoy</button>
        <input type="date" class="datepicker" autocomplete="off" value="<?= $lunes ?>" onchange="conenct_post_arr('game', 'set_session', ['lunes', $(this).val()])">
        <button class="rondo cyan_but" style="float: right;" onclick="conenct_post_arr('game', 'set_session', ['juego_desp', <?= ($desp == 7 ? "1" : "7") ?>])"><?= ($desp == 7 ? "Mensual" : "Semanal") ?></button>
    </div>
    <div class="col-lg-1 col-md-2 sm-hide">
        <i class="fas fa-chevron-left change_stats l-50" onclick="conenct_post_arr('game', 'set_session', ['lunes', '<?= date('Y-m-d', strtotime(($desp == 7 ? $lunes . ' -' . $desp . " days" : date("Y-m-01", strtotime($lunes)) . " -1 month"))) ?>'])"></i>
    </div>
    <div class="col-lg-10 col-md-12">
        <div style="font-weight: 600;">Tiempo de juego:</div>
        <canvas id="myChart" width="400" height="200"></canvas>
        <?php
        if ($desp == 7) {
            $arr_hours = get_stats(0, $lunes);
        } else {
            $arr_hours = get_stats(1, $lunes);
        }
        $label = array_keys($arr_hours)[0];
//        print_r($arr_hours);
        ?>

        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_keys($arr_hours[$label])) ?>,
                    datasets: [
<?php
$count = 0;
foreach ($arr_hours as $key => $value) {
    $bol = true;
    if ($key == "Sin Partida") {
        if (array_sum($arr_hours[$key]) == 0) {
            $bol = false;
        }
    }
    if ($bol) {
        if ($count) {
            echo ",";
        }
        echo "{";
        echo "  label: '" . $key . "',";
        echo "  data: " . json_encode(array_values($value)) . ",";
        echo "  backgroundColor: ['" . get_color_trans($count + 1, 0.2) . "'],";
        echo "  borderColor: ['" . get_color_trans($count + 1, 1) . "'],
borderWidth: 1";
        echo "}";
        $count++;
    }
}
?>]
                },
                options: {
                    scales: {
                        xAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                        y: {
                            suggestedMax: 1,
                            ticks: {
                                stepSize: 0.25,
                                callback: function (value, index, values) {
                                    var horas = value;
                                    var horas_int = Math.trunc(horas);
                                    var minutes = (horas - horas_int) * 60;
                                    var minutes_int = Math.trunc(minutes);
                                    return horas_int + "h:" + minutes_int + "m";
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    var label = context.dataset.label || '';

                                    if (label) {
                                        label += ': ';
                                    }
                                    var value = context.parsed.y;
                                    var horas = value;
                                    var horas_int = Math.trunc(horas);
                                    var minutes = (horas - horas_int) * 60;
                                    var minutes_int = Math.trunc(minutes);
                                    return label + horas_int + "h:" + minutes_int + "m";
                                }
                            }
                        }
                    },
                    ticks: {
                        beginAtZero: true,
                        max: 1,
                        suggestedMin: 0
                    }
                }
            });
        </script>
    </div>
    <div class="col-lg-1 col-md-2 sm-hide">
        <i class="fas fa-chevron-right change_stats r-50" onclick="conenct_post_arr('game', 'set_session', ['lunes', '<?= date('Y-m-d', strtotime(($desp == 7 ? $lunes . ' +' . $desp . " days" : date("Y-m-01", strtotime($lunes)) . " +1 month"))) ?>'])"></i>
    </div>
</div>

<div class="oscur" id="new_partida">
    <div class="white">
        <button class="close rondo2 cyan_but" onclick="$('.oscur').hide()">
            <i class="fa fa-times"></i>
        </button>
        <form id="add_partida">
            <h3>Nueva partida</h3>
            <input type="hidden" name="funcion" value="adquirir_partida">
            <input type="hidden" name="idJuegoPlataforma" id="idJuegoPlataforma" value="<?= $_GET["id2"] ?>" required="">
            <div>Fecha de inicio: <span style="color: red;">*</span>: <input type="text" autocomplete="off" required="" class="datepicker" name="fechaIni"> </div>
            <div>Fecha de finalización: <input type="text" autocomplete="off" class="datepicker" name="fechaFin"> </div>
            <div>Observación: <br>
                <textarea name="obs"></textarea>
            </div>
            <input type="submit">
        </form>
    </div>
</div>
</form>
</div>
</div>

<div class="oscur" id="add_time">
    <div class="white">
        <button class="close rondo2 cyan_but" onclick="$('.oscur').hide()">
            <i class="fa fa-times"></i>
        </button>
        <form id="add_time_to">
            <h3>Añadir tiempo de juego</h3>
            <input type="hidden" name="funcion" value="add_time">
            <input type="hidden" name="idUsuarioJuegoPlataforma" id="idJuegoPlataforma" value="<?= $_GET["id2"] ?>" required="">
            <input type="hidden" name="idPartida" id="idpartida" value="<" required="">
            <div>Fecha de inicio <span style="color: red;">*</span>: <input type="text" autocomplete="off" required="" class="datetimepicker" name="fechaIni"> </div>
            <div>Fecha de fin <span style="color: red;">*</span>: <input type="text" autocomplete="off" required="" class="datetimepicker" name="fechaFin"> </div>
            <input type="submit">
        </form>
    </div>
</div>
</form>
</div>
</div>
<style>
    body{
        background: url('<?= $img ?>');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
    .container-fluid{
        background: rgba(255,255,255,0.9);
        padding-bottom: 1px;
    }
</style>
<script>
    $(".datatable").DataTable();
    $("#add_partida").submit(function (e) {
        var myArray = new FormData($(this)[0]);
        conenction_post("user", myArray);
        e.preventDefault();
    });
    $("#add_time_to").submit(function (e) {
        var myArray = new FormData($(this)[0]);
        conenction_post("user", myArray);
        e.preventDefault();
    });
    function add_time(partida) {
        $("#idpartida").val(partida);
        $("#add_time").show();
    }
</script>
<input type="submit" value="" />
