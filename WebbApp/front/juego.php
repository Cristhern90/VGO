<?php
$sql = "SELECT j.*, mj.url, s.name serie, (
            SELECT GROUP_CONCAT(CONCAT(name,'|',logo) SEPARATOR ',') 
            FROM compania c
            INNER JOIN juegocompania jc ON jc.idCompania = c.idCompania
            WHERE jc.idJuego = j.idjuego
        ) desarrolladoras 
        FROM juego j 
        LEFT JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
        LEFT JOIN juegoserie sj ON sj.idJuego = j.idJuego 
        LEFT JOIN serie s ON s.idSerie = sj.idSerie
        WHERE j.idJuego = " . $_GET["id"];

$query = get_query($sql);

$row = $query->fetch_assoc();
?>

<div class="row">
    <div class="col-sm-12 col-lg-9">
        <h1><?= $row["name"] ?></h1>
        <ul style="list-style: none; padding: 0;">
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
                    if ($arr_de[1]) {
                        ?>
                        <?= $arr_de[0] ?> <img src="<?= $arr_de[1] ?>" style="width: 2em;">
                        <?php
                    } else {
                        echo $arr_de[0];
                    }
                    $count++;
                }
                ?></li>
            <li>
                Plataformas:
                <table class="datatable w-100 text-center">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nombre</th>
                            <th>Región</th>
                            <th>Fecha estreno</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_p = "SELECT jp.idJuegoPlataforma, p.idPlataforma,p.logo, p.local, jp.region, p.name, DATE_FORMAT(jp.fechaEstreno, '%Y-%m-%d') fechaEstreno ";
                        if (isset($user->id)) {
                            $sql_p .= ", (
                                            SELECT count(*) FROM usuariojuegoplataforma ujp WHERE ujp.idJuegoPlataforma = jp.idJuegoPlataforma AND ujp.idUsuario = " . $user->id . "
                                        ) count ";
                        }
                        $sql_p .= "FROM plataforma p
                                INNER JOIN juegoplataforma jp ON jp.idPlataforma = p.idPlataforma
                                WHERE jp.idJuego = " . $_GET["id"] . "";
                        $query_p = get_query($sql_p);
                        while ($row_p = $query_p->fetch_assoc()) {
                            ?>
                            <tr>
                                <td>
                                    <img src="<?= ($row_p["local"]?$url.$row_p["logo"]:$row_p["logo"]) ?>" style="width: 2em;">
                                </td>
                                <td><?= $row_p["name"] ?></td>
                                <td><?= get_region($row_p["region"]) ?></td>
                                <td><?= $row_p["fechaEstreno"] ?></td>
                                <td>
                                    <?php if (isset($user->id)) { ?>
                                        <?php if ($row_p["count"]) { ?>
<!--                                            <button onclick="abrir_adquirir_juego(<?= $row_p["idJuegoPlataforma"] ?>, '<?= $row_p["name"] ?>')" class="cyan_but rondo2">
                                                <i class="fas fa-link"></i>
                                            </button>-->
                                        <?php } else { ?>
                                            <button onclick="abrir_adquirir_juego(<?= $row_p["idJuegoPlataforma"] ?>, '<?= $row_p["name"] ?>');$('#add_game_plat').show()" class="cyan_but rondo2">
                                                <i class="fas fa-link"></i>
                                            </button>
                                        <?php } ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </li>
        </ul>
    </div>
    <div class="col-sm-12 col-lg-3">
        <img src="<?= $row["url"] ?>" class="w-100" alt="...">
    </div>
</div>
<div class="oscur" id="add_game_plat">
    <div class="white">
        <button onclick="$('.oscur').hide()" class="close rondo2 cyan_but">
            <i class="fa fa-times"></i>
        </button>
        <form id="adquirir_juego">
            <input type="hidden" name="funcion" value="adquirir_juego">
            <input type="hidden" name="idUsuario" value="<?= $user->id ?>">
            <input type="hidden" name="idJuegoPlataforma" id="idJuegoPlataforma" value="" required="">
            <h3>Adquirir juego</h3>
            <div>Juego: <?= $row["name"] ?></div>
            <div>Plataforma: <span class="plat_name"></span></div>
            <div>Distribuidor<span style="color: red;">*</span>: 
                <select name="idDistribuidor" required="">
                    <option value="">Selecciona un distribuidor o tienda</option>
                    <?php
                    $sql_dis = "SELECT * FROM distribuidor ORDER BY name";
                    $query_dis = get_query($sql_dis);
                    while ($row_dis = $query_dis->fetch_assoc()) {
                        ?>
                        <option value="<?= $row_dis["idDistribuidor"] ?>"><?= $row_dis["name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>Fecha de adquisición<span style="color: red;">*</span>: <input type="text" autocomplete="off" required="" id="datepicker" name="fechaAdquisicion"> 
            </div>
            <div>Coste de adquisición<span style="color: red;">*</span>: <input type="number" step="0.01" required="" name="coste">
            </div>
            <div>Observación: <br>
                <textarea name="obs"></textarea>
            </div>
            <input type="submit">
        </form>
    </div>
</div>
<script>
    $(".datatable").DataTable();
    $("#datepicker").datepicker();
    $("#act-serie").click(function () {
        conenct_post("collection", "act_serie",<?= $_GET["id"] ?>);
    });

    function abrir_adquirir_juego(plat_id, plat_name) {
        $(".plat_name").text(plat_name);
        $("#idJuegoPlataforma").val(plat_id);
    }
    $("#adquirir_juego").submit(function (e) {
        var myArray = new FormData($(this)[0]);
        conenction_post("user", myArray);
        e.preventDefault();
    });
</script>
