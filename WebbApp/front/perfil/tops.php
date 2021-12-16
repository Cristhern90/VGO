<?php
$order = "";
$session_mod = "top_";
$idname = "idTop";

include './includes/list_ini.php';
?>
<div class="row">
    <div class="col-sm-6">
        <h2>
            Tops: <span id="count"></span>
            <button onclick="$('#nuevo_top').show()" class="rondo2 cyan_but"><i class='fas fa-plus'></i></button>
        </h2>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        Ordenar por: 
        <select onchange="conenct_post_arr('game', 'set_order_1', ['<?= $session_mod ?>order', $(this).val()])">
            <option value="name" <?= ($order == "name" ? "selected" : "") ?>>Nombre</option>
            <option value="FechaCreacion" <?= ($order == "FechaCreacion" ? "selected" : "") ?>>Fecha Añadido</option>
        </select>
        <select onchange="conenct_post_arr('game', 'set_order_2', ['<?= $session_mod ?>order2', $(this).val()])">
            <option value="ASC" <?= ($order2 == "ASC" ? "selected" : "") ?>>ASC</option>
            <option value="DESC" <?= ($order2 == "DESC" ? "selected" : "") ?>>DESC</option>
        </select>
    </div>
</div>
<?php
$sql = "
SELECT *, CASE 
	WHEN t.tabla = 'juego' THEN
	(
	    SELECT GROUP_CONCAT(mj.url ORDER BY tp.posicion) 
            FROM topelemento te
            INNER JOIN topposicion tp ON tp.idTopPosicion = te.idTopPosicion
	    INNER JOIN juego j ON j.idJuego = te.idReferencia 
            INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND mj.tipo = 'cover'
	    WHERE te.idTop = t.idTop
	) 
    ELSE ''
END covers 
FROM top t
WHERE t.idUsuario = " . $user->id;
$sql .= " AND " . $order . " " . ($order2 == "ASC" ? ">" : "<") . " '" . addslashes($limit) . "'";

$sql_paging = "
SELECT " . $order . " 
FROM top t
WHERE t.idUsuario = " . $user->id . "";


$sql .= " ORDER BY " . $order . " " . $order2 . " Limit 12";

$sql_paging .= " ORDER BY " . $order . " " . $order2 . "";

$query = get_query($sql);

$query_paging = get_query($sql_paging);
?>
<div class="row">
    <?php
    $count = 0;
    $next = "";
    $ant = "";
    while ($row = $query->fetch_assoc()) {
        $next = $row["idTop"];
        if ($count == 0) {
            $ant = $row["idTop"];
        }
        ?>
        <div class="item_juegos col-sm-6 col-md-3 col-lg-2" id="item-<?= $row["idTop"] ?>" onclick="location.href = '<?= $url ?>/perfil/top/<?= $row["idTop"] ?>'"> 
            <div class="item_juegos_content row">
                <div class="w-100">
                    <?php
                    if (isset($row["covers"])) {
                        $arr_covers = explode(",", $row["covers"]);
                    }else{
                        $arr_covers = array();
                    }
                    ?>
                    <div class="row" style="min-height: 90%;width: 90%;margin-left: 5%;">
                        <div class="col-sm-6 gen_img_slider">
                            <img src="<?= (isset($arr_covers[0]) ? $arr_covers[0] : $url . "/imagenes/cover_void.png") ?>" class="w-100 " alt="...">
                        </div>
                        <div class="col-sm-6 gen_img_slider">
                            <img src="<?= (isset($arr_covers[1]) ? $arr_covers[1] : $url . "/imagenes/cover_void.png") ?>" class="w-100 " alt="...">
                        </div>
                        <div class="col-sm-6 gen_img_slider">
                            <img src="<?= (isset($arr_covers[2]) ? $arr_covers[2] : $url . "/imagenes/cover_void.png") ?>" class="w-100 " alt="...">
                        </div>
                        <div class="col-sm-6 gen_img_slider">
                            <img src="<?= (isset($arr_covers[3]) ? $arr_covers[3] : $url . "/imagenes/cover_void.png") ?>" class="w-100 " alt="...">
                        </div>
                        <div class="w-100 text-center">Top <?= $row["cantidad"] ?> <?= $row["name"] ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $count++;
    }
    ?>
</div>
<div class="oscur" id="nuevo_top">
    <div class="white">
        <button class="close rondo2 cyan_but" onclick="$('.oscur').hide()">
            <i class="fa fa-times"></i>
        </button>
        <form id="new_top">
            <h3>Nuevo Top</h3>
            <input type="hidden" name="funcion" value="new_top">
            <div>Nombre<span style="color: red;">*</span>: <input type="text" autocomplete="off" required="" name="name"> </div>
            <div>Cantidad<span style="color: red;">*</span>: <input type="text" autocomplete="off" required="" name="cantidad"> </div>
            <input type="submit">
        </form>
    </div>
</div>
<?php
include './includes/pagination.php';
?>
<script>
    $(".datatable").DataTable();
    $("#new_top").submit(function (e) {
        var myArray = new FormData($(this)[0]);
        conenction_post("top", myArray);
        e.preventDefault();
    });
</script>