<?php
$order = "";
$session_mod = "game_";
$idname = "idJuego";

if (!isset($_COOKIE[$session_mod . "page"])) {
    $_COOKIE[$session_mod . "page"] = 1;
}

include './includes/list_ini.php';
?>
<div class="row">
    <div class="col-sm-6">
        <h2>
            Juegos: <span id="count"></span>
            <button onclick="location.href = '<?= $url ?>/searchAPI'" class="rondo2 cyan_but"><i class='fas fa-plus'></i></button>
        </h2>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        Ordenar por: 
        <select onchange="conenct_post_arr('game', 'set_order_1', ['<?= $session_mod ?>order', $(this).val()])">
            <option value="name" <?= ($order == "name" ? "selected" : "") ?>>Nombre</option>
            <option value="PrimeraFechaEstreno" <?= ($order == "PrimeraFechaEstreno" ? "selected" : "") ?>>Fecha Estreno</option>
            <option value="FechaCreacion" <?= ($order == "FechaCreacion" ? "selected" : "") ?>>Fecha Añadido</option>
        </select>
        <select onchange="conenct_post_arr('game', 'set_order_2', ['<?= $session_mod ?>order2', $(this).val()])">
            <option value="ASC" <?= ($order2 == "ASC" ? "selected" : "") ?>>ASC</option>
            <option value="DESC" <?= ($order2 == "DESC" ? "selected" : "") ?>>DESC</option>
        </select>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-3">Series
                <select id="filt-serie" style="width: 100%;">
                    <option value="">Todas las series</option>
                    <?php
                    $sql_filt1 = "SELECT * FROM serie ORDER BY name";
                    $query_filt1 = get_query($sql_filt1);
                    while ($row_filt1 = $query_filt1->fetch_assoc()) {
                        ?>
                        <option <?= (isset($_COOKIE["serie"]) ? ($_COOKIE["serie"] == $row_filt1["idSerie"] ? "selected" : "") : "") ?> value="<?= $row_filt1["idSerie"] ?>"><?= $row_filt1["name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-3">Generos
                <select id="filt-genero" style="width: 100%;">
                    <option value="">Todos los géneros</option>
                    <?php
                    $sql_filt2 = "SELECT * FROM genero ORDER BY name";
                    $query_filt2 = get_query($sql_filt2);
                    while ($row_filt2 = $query_filt2->fetch_assoc()) {
                        ?>
                        <option <?= (isset($_COOKIE["genero"]) ? ($_COOKIE["genero"] == $row_filt2["idGenero"] ? "selected" : "") : "") ?> value="<?= $row_filt2["idGenero"] ?>"><?= $row_filt2["name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-3">Plataformas
                <select id="filt-plataforma" style="width: 100%;">
                    <option value="">Todas las plataformas</option>
                    <?php
                    $sql_filt3 = "SELECT * FROM plataforma ORDER BY name";
                    $query_filt3 = get_query($sql_filt3);
                    while ($row_filt3 = $query_filt3->fetch_assoc()) {
                        ?>
                        <option <?= (isset($_COOKIE["plataforma"]) ? ($_COOKIE["plataforma"] == $row_filt3["idPlataforma"] ? "selected" : "") : "") ?> value="<?= $row_filt3["idPlataforma"] ?>"><?= $row_filt3["name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-3">Desarrolladoras
                <select id="filt-compania" style="width: 100%;">
                    <option value="">Todas las desarrolladoras</option>
                    <?php
                    $sql_filt3 = "SELECT * FROM compania ORDER BY name";
                    $query_filt3 = get_query($sql_filt3);
                    while ($row_filt3 = $query_filt3->fetch_assoc()) {
                        ?>
                        <option <?= (isset($_COOKIE["compania"]) ? ($_COOKIE["compania"] == $row_filt3["idCompania"] ? "selected" : "") : "") ?> value="<?= $row_filt3["idCompania"] ?>"><?= $row_filt3["name"] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
</div>
<?php
$sql = "
        SELECT j.*, mj.url, (
            SELECT GROUP_CONCAT(DISTINCT CONCAT(p.logo, ',',p.colorFondo, ',', p.local, ',', p.rondo) SEPARATOR '|') 
            FROM plataforma p
            INNER JOIN juegoplataforma jp ON jp.idPlataforma = p.idPlataforma
            WHERE jp.idJuego = j.idJuego
            ORDER BY fechaEstreno DESC
        ) logos 
        FROM juego j
        INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'";
if (isset($_COOKIE["serie"])) {
    $sql .= " INNER JOIN juegoserie js ON js.idJuego = j.idJuego AND js.idSerie = " . $_COOKIE["serie"] . " ";
}
if (isset($_COOKIE["genero"])) {
    $sql .= " INNER JOIN juegogenero jg ON jg.idJuego = j.idJuego AND jg.idGenero = " . $_COOKIE["genero"] . " ";
}
if (isset($_COOKIE["plataforma"])) {
    $sql .= " INNER JOIN juegoplataforma jp ON jp.idJuego = j.idJuego AND jp.idPlataforma = " . $_COOKIE["plataforma"] . " AND (region = 8 OR region = 5) ";
}
if (isset($_COOKIE["compania"])) {
    $sql .= " INNER JOIN juegocompania jc ON jc.idJuego = j.idJuego AND jc.idCompania = " . $_COOKIE["compania"] . " ";
}

$sql .= " WHERE 1 = 1";
$sql_paging = "
        SELECT j.* 
        FROM juego j ";
if (isset($_COOKIE["serie"])) {
    $sql_paging .= " INNER JOIN juegoserie js ON js.idJuego = j.idJuego AND js.idSerie = " . $_COOKIE["serie"] . " ";
}
if (isset($_COOKIE["genero"])) {
    $sql_paging .= " INNER JOIN juegogenero jg ON jg.idJuego = j.idJuego AND jg.idGenero = " . $_COOKIE["genero"] . " ";
}
if (isset($_COOKIE["plataforma"])) {
    $sql_paging .= " INNER JOIN juegoplataforma jp ON jp.idJuego = j.idJuego AND jp.idPlataforma = " . $_COOKIE["plataforma"] . " AND (region = 8 OR region = 5) ";
}
if (isset($_COOKIE["compania"])) {
    $sql_paging .= " INNER JOIN juegocompania jc ON jc.idJuego = j.idJuego AND jc.idCompania = " . $_COOKIE["compania"] . " ";
}
$sql_paging .= " WHERE 1=1 ";

if (isset($_COOKIE[$session_mod . "titulo"])) {
    $sql .= " AND j.name LIKE '%" . $_COOKIE[$session_mod . "titulo"] . "%' ";
    $sql_paging .= " AND j.name LIKE '%" . $_COOKIE[$session_mod . "titulo"] . "%' ";
}


$sql .= " ORDER BY " . $order . " " . $order2;

//echo $sql;

$sql_paging .= "ORDER BY " . $order . " " . $order2 . "";

$query = get_query($sql);
$query_paging = get_query($sql_paging);

include './includes/pagination2.php';
?>
<div class="row">
    <?php
    $count = 0;
    $next = "";
    $ant = "";
    while ($row = $query->fetch_assoc()) {
        $next = $row["idJuego"];
        if ($count == 0) {
            $ant = $row["idJuego"];
        }
        $val = ($_COOKIE[$session_mod . "page"]) * 12;
        $val2 = ($_COOKIE[$session_mod . "page"] - 1) * 12;
//        echo $count.": ".$val . "--> ".$val2."<br>";
        if ($count < $val && $count >= $val2) {
            ?>
            <div class="item_juegos col-12 col-sm-6 col-md-3 col-lg-2" id="item-<?= $row["idJuego"] ?>" onclick="location.href = '<?= $url ?>/juego/<?= $row["idJuego"] ?>'"> 
                <div class="item_juegos_content row">
                    <div class="plat_imgs_cont">
                        <?php
                        $logos_arr = explode("|", $row["logos"]);
                        $count_img = 0;
                        foreach ($logos_arr as $key => $dats_logo) {

                            $logo_arr = explode(",", $dats_logo);
                            $logo = $logo_arr[0];
                            $fondo = $logo_arr[1];
                            $local = "";
                            if ($logo_arr[2]) {
                                $local = $url;
                            }

                            $rondo = $logo_arr[3];
                            if ($count_img <= 3) {
                                ?>
                                <img src="<?= $local . $logo ?>" class="plat_img <?= ($fondo ? "rondo" . $rondo : "") ?> col-sm-2" style="left: calc(100%/6 * <?= $count ?>); <?= ($fondo ? "background-color:" . $fondo : "") ?>" >
                                <?php
                            }
                            $count_img++;
                        }
                        if (($count - 4) > 0) {
                            ?>
                            <img src="<?= $url ?>/imagenes/plat_logos/more.png" class="plat_img rondo1 col-sm-2" style="left: calc(100%/6 * <?= $count ?>);" >
                        <?php } ?>
                    </div>
                    <img src="<?= $row["url"] ?>" class="w-100 game_img" alt="...">
                </div>
            </div>
            <?php
        }
        $count++;
    }
    ?>
</div>
<?php
include './includes/pagination.php';
?>
<script>
    $("#filt-serie").change(function () {
        if ($(this).val()) {
            conenct_post_arr('game', 'set_session', ['serie', $(this).val()]);
        } else {
            conenct_post_arr('game', 'del_session', ['serie'])
        }
    });
    $("#filt-genero").change(function () {
        if ($(this).val()) {
            conenct_post_arr('game', 'set_session', ['genero', $(this).val()]);
        } else {
            conenct_post_arr('game', 'del_session', ['genero'])
        }
    });
    $("#filt-plataforma").change(function () {
        if ($(this).val()) {
            conenct_post_arr('game', 'set_session', ['plataforma', $(this).val()]);
        } else {
            conenct_post_arr('game', 'del_session', ['plataforma'])
        }
    });
    $("#filt-compania").change(function () {
        if ($(this).val()) {
            conenct_post_arr('game', 'set_session', ['compania', $(this).val()]);
        } else {
            conenct_post_arr('game', 'del_session', ['compania'])
        }
    });
</script>