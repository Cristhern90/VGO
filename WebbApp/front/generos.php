<?php
//unset($_COOKIE["PrimeraFechaEstreno"]);
$order = "";
$session_mod = "genero_";
if (!isset($_COOKIE[$session_mod . "order"])) {
    $order = "name";
} else {
    $order = $_COOKIE[$session_mod . "order"];
}
$order2 = "";
if (!isset($_COOKIE[$session_mod . "order2"])) {
    $order2 = "ASC";
} else {
    $order2 = $_COOKIE[$session_mod . "order2"];
}
$page = "";
if (!isset($_COOKIE[$session_mod . "page"])) {
    $page = 1;
} else {
    $page = $_COOKIE[$session_mod . "page"];
}

$limit = "";
if ($page == 1) {
    if ($order == "name") {
        if ($order2 == "ASC") {
            $limit = "";
        } else {
            $limit = "ZZZZZZZZZZ";
        }
    } else {
        if ($order2 == "ASC") {
            $limit = "1900-01-01";
        } else {
            $limit = "2900-01-01";
        }
    }
} else {
    $limit = $_COOKIE[$session_mod . "limit"];
}

//foreach ($_COOKIE as $key => $value) {
//    echo $key . " = " . $value . "<br>";
//}

$idname = "idGenero";
?>
<div class="row">
    <div class="col-sm-6">
        <h2>Generos:</h2>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        Ordenar por: 
        <select onchange="conenct_post_arr('genre', 'set_order_1', ['<?= $session_mod ?>order', $(this).val()])">
            <option value="name" <?= ($order == "name" ? "selected" : "") ?>>Nombre</option>
            <option value="PrimeraFechaEstreno" <?= ($order == "PrimeraFechaEstreno" ? "selected" : "") ?>>Fecha Estreno</option>
            <option value="FechaCreacion" <?= ($order == "FechaCreacion" ? "selected" : "") ?>>Fecha Añadido</option>
        </select>
        <select onchange="conenct_post_arr('genre', 'set_session', ['<?= $session_mod ?>order2', $(this).val()])">
            <option value="ASC" <?= ($order2 == "ASC" ? "selected" : "") ?>>ASC</option>
            <option value="DESC" <?= ($order2 == "DESC" ? "selected" : "") ?>>DESC</option>
        </select>
    </div>
</div>
<?php
$sql = "
        SELECT g.*,(
            SELECT GROUP_CONCAT(mj2.url ORDER BY j2.PrimeraFechaEstreno DESC LIMIT 4)
            FROM juegogenero jg
            INNER JOIN juego j2 ON j2.idJuego = jg.idJuego
            INNER JOIN mediajuego mj2 ON mj2.idJuego = j2.idJuego AND mj2.tipo = 'cover'
            WHERE jg.idGenero = g.idGenero
            ORDER BY j2.PrimeraFechaEstreno DESC LIMIT 4
        ) covers 
        FROM genero g";

$sql .= " WHERE " . $order . " " . ($order2 == "ASC" ? ">" : "<") . " '" . $limit . "'";

$sql .= " ORDER BY " . $order . " " . $order2 . " Limit 12";
//echo $sql;
$query = get_query($sql);

$sql_paging = "
        SELECT j.* 
        FROM genero j
        ORDER BY " . $order . " " . $order2 . "";
$query_paging = get_query($sql_paging);
?>
<div class="row">
    <?php
    $count = 0;
    $next = "";
    $ant = "";
    while ($row = $query->fetch_assoc()) {
        $next = $row[$idname];
        if ($count == 0) {
            $ant = $row[$idname];
        }
        ?>
        <div class="item_generos col-sm-6 col-md-3 col-lg-2" id="item-<?= $row[$idname] ?>"> 
            <div class="item_generos_content row">
                <div class="w-100">
                    <?php
                    $arr_covers = explode(",", $row["covers"]);
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
                        <div class="w-100 text-center"><?= $row["name"] ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $count++;
    }
    ?>
</div>
<?php
include './includes/pagination.php';
?>