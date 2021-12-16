<?php
$sql = "
SELECT * 
FROM top t
WHERE t.idTop = " . $_GET["id2"];
//echo $sql;
$query = get_query($sql);
$row = $query->fetch_assoc();

$sql_flt = "
SELECT * 
FROM toprequisito tr
WHERE tr.idTop = " . $_GET["id2"];

$query_filt = get_query($sql_flt);
$query_filt2 = get_query($sql_flt);

$sql_elements = "
SELECT j.*, mj.url  
FROM juego j
INNER JOIN juegoplataforma jp ON jp.idJuego = j.idJuego
INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND mj.tipo = 'cover'
INNER JOIN usuarioJuegoPlataforma ujp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma ";
while ($row_filt = $query_filt->fetch_assoc()) {
    if ($row_filt["tabla"] == "partida") {
        $sql_elements .= "INNER JOIN partida par ON par.idUsuariojuegoplataforma = ujp.idUsuariojuegoplataforma ";
    }
}
$sql_elements .= " WHERE ujp.idUsuario = " . $user->id;
while ($row_filt2 = $query_filt2->fetch_assoc()) {
    if ($row_filt2["tabla"] == "partida") {
        if ($row_filt2["valor1"] == "Si") {
            $sql_elements .= " AND par." . $row_filt2["name"] . " IS NOT NULL";
        }else{
            $sql_elements .= " AND " . $row_filt2["tipo"] . "(par." . $row_filt2["name"] . ") = '" . $row_filt2["valor1"] . "'";
        }
    }
    if ($row_filt2["tabla"] == "juego") {
        $sql_elements .= " AND " . $row_filt2["tipo"] . "(j." . $row_filt2["name"] . ") = '" . $row_filt2["valor1"] . "'";
    }
    if ($row_filt2["tabla"] == "juegoplataforma") {
        $sql_elements .= " AND " . $row_filt2["tipo"] . "(jp." . $row_filt2["name"] . ") = '" . $row_filt2["valor1"] . "'";
    }
}
$sql_elements .= " AND (
    SELECT count(*) 
    FROM topelemento te
    WHERE te.idTop = " . $_GET["id2"] . " AND te.idReferencia = j.idJuego
) = 0 
ORDER BY j.name";
//echo $sql_elements;
$query_elements = get_query($sql_elements);
$count_elements = 0;

$sql_pos = "SELECT * FROM topposicion WHERE idTop = " . $_GET["id2"] . " ORDER BY posicion";
$query_pos = get_query($sql_pos);
?>
<div class="row" style="margin-bottom: 25px;">
    <div class="col-md-6">
        <h1>Top <?= $row["cantidad"] ?> <?= $row["name"] ?> <button class="red_but rounded-3" onclick="conenct_post('top', 'del_top',<?= $_GET["id2"] ?>)"><i class="fa fa-trash"></i></button></h1>
    </div>
    <div class="col-md-6 text-end">
        <h3>Requisitos</h3>
        <?php
        $sql_req = "SELECT * 
                FROM toprequisito tr
                WHERE idTop = " . $_GET["id2"];
        $query_req = get_query($sql_req);
        while ($row_req = $query_req->fetch_assoc()) {
            ?>
            <p><?= ucfirst($row_req["name"]) ?> <?= ($row_req["valor2"] ? "entre " . $row_req["valor1"] . " y " . $row_req["valor2"] : " = " . $row_req["valor1"]) ?> <button class="red_but rounded-3" onclick="conenct_post('top', 'del_req',<?= $row_req["idTopRequisito"] ?>)"><i class="fa fa-times"></i></button></p>
        <?php } ?>
        <button class="cyan_but rounded-3" onclick="$('#more_requisitos').show()"><i class="fa fa-plus"></i></button>
    </div>
</div>
<?php
while ($row_pos = $query_pos->fetch_assoc()) {
    ?>
    <div class="row">
        <div class="col-2 text-center" style="font-size: 1.5em;padding: calc((100px - 1.5em)/2)">
            <?= $row_pos["name"] ?>
        </div>
        <!--<div class="col-10 ">-->
        <div class="col-10 row border border-3 rounded-3 sortable" data-pos="<?= $row_pos["idTopPosicion"] ?>" style="min-height: 100px;margin-bottom: 10px;padding: 0;">
            <?php
            $sql_pos_ele = "SELECT j.*, mj.url 
                            FROM topelemento te INNER JOIN juego j ON j.idJuego = te.idReferencia 
                            INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND mj.tipo = 'cover' 
                            WHERE idTop = " . $_GET["id2"] . " AND idTopPosicion = " . $row_pos["idTopPosicion"];
            $query_pos_ele = get_query($sql_pos_ele);
            while ($row_pos_ele = $query_pos_ele->fetch_assoc()) {
                ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center draggable" style="margin: 5px 0px;" data-juego="<?= $row_pos_ele["idJuego"] ?>">
                    <img src="<?= $row_pos_ele["url"] ?>" class="w-100">
                </div>
            <?php }
            ?>
        </div>
        <!--</div>-->
    </div>
<?php } ?>

<div class="row"  style="margin-bottom: 25px;">
    <div class="col-12">
        <h2>Juegos: <span id="count_elements"></span></h2>
    </div>
    <div class="sortable row" >
        <?php
        while ($row_elements = $query_elements->fetch_assoc()) {
            $count_elements++;
            ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center draggable " style="margin: 5px 0px;" data-juego="<?= $row_elements["idJuego"] ?>">
                <img src="<?= $row_elements["url"] ?>" class="w-100">
            </div>
        <?php } ?>
    </div>
</div>
<div class="oscur" id="more_requisitos">
    <div class="white">
        <button class="close rondo2 cyan_but" onclick="$('.oscur').hide()">
            <i class="fa fa-times"></i>
        </button>
        <form id="add_req">
            <h3>Añadir requisitos</h3>
            <input type="hidden" name="funcion" value="add_req">
            <input type="hidden" name="idTop" value="<?= $_GET["id2"] ?>">
            <div>Filtro<span style="color: red;">*</span>: 
                <select name="req" required="">
                    <option value="juegoplataforma_FechaEstreno">Fecha estreno</option>
                    <option value="partida_fechaFin">Fecha fin de partda</option>
                    <option value="partida_fechaInicio">Fecha inicio de partida</option>
                </select>
            </div>
            <div>Tipo<span style="color: red;">*</span>: 
                <select name="tipo" required="">
                    <option value="date">Fecha</option>
                    <option value="year">Año</option>
                </select>
            </div>
            <div>
                <div>Valor 1<span style="color: red;">*</span>: <input type="text" autocomplete="off" required="" name="valor1"> </div>
            </div>
            <div>
                <div>Valor 2: <input type="text" autocomplete="off" name="valor2"> </div>
            </div>
            <input type="submit">
        </form>
    </div>
</div>
<script>
    $("#count_elements").text("<?= $count_elements ?>");

    $("#add_req").submit(function (e) {
        var myArray = new FormData($(this)[0]);
        conenction_post("top", myArray);
        e.preventDefault();
    });

    var height = $(".draggable").height();
    $(document).ready(function () {
        $(".sortable").sortable({
            revert: true
        });

        $(".draggable").draggable({
            cursor: "move",
            //            cursorAt: {top: 50, left: 50},
            connectToSortable: ".sortable",
            start: function (event, ui) {
                //                var height = $(this).height();
                //                console.log(height);
                //                $(this).css("width", "100px");
                //                $(this).css("height", "100px");
            },
            stop: function (event, ui) {
                //                $(this).css("width", "");
                //                $(this).css("height", "");
                var juego = $(this).data("juego");
                var pos = $(this).parent().data("pos");
                conenct_post_arr("top", "set_element", [juego, "<?= $_GET["id2"] ?>", pos]);

            }
        });
    });
</script>