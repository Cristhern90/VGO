<?php
//unset($_COOKIE["PrimeraFechaEstreno"]);
$order = "";
$session_mod = "my_game_";
$idname = "idUsuarioJuegoPlataforma";

include './includes/list_ini.php';
?>
<div class="row">
    <div class="col-sm-6">
        <h2>
            Mis juegos: <span id="count"></span>
            <button onclick="location.href = '<?= $url ?>/juegos'" class="rondo2 cyan_but"><i class='fas fa-plus'></i></button>
        </h2>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        Ordenar por: 
        <select onchange="conenct_post_arr('game', 'set_order_1', ['<?= $session_mod ?>order', $(this).val()])">
            <option value="j.name" <?= ($order == "j.name" ? "selected" : "") ?>>Nombre</option>
            <option value="jp.FechaEstreno" <?= ($order == "jp.FechaEstreno" ? "selected" : "") ?>>Fecha Estreno</option>
        </select>
        <select onchange="conenct_post_arr('game', 'set_order_2', ['<?= $session_mod ?>order2', $(this).val()])">
            <option value="ASC" <?= ($order2 == "ASC" ? "selected" : "") ?>>ASC</option>
            <option value="DESC" <?= ($order2 == "DESC" ? "selected" : "") ?>>DESC</option>
        </select>
    </div>
</div>
<?php
$sql = "
SELECT p.logo, p.colorFondo, p.local, p.rondo, j.name titulo, d.name distribuidor, d.logo dist_logo, ujp.observacion, mj.url, ujp.idUsuarioJuegoPlataforma 
FROM usuariojuegoplataforma ujp 
INNER JOIN juegoplataforma jp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma 
INNER JOIN plataforma p ON jp.idPlataforma = p.idPlataforma 
INNER JOIN juego j ON jp.idjuego = j.idjuego 
INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
INNER JOIN distribuidor d ON d.idDistribuidor = ujp.idDistribuidor 
WHERE ujp.idUsuario = " . $user->id . "";
$sql .= " AND " . $order . " " . ($order2 == "ASC" ? ">" : "<") . " '" . addslashes($limit) . "'";

$sql_paging = "
        SELECT ".$order." 
        FROM usuariojuegoplataforma ujp
        INNER JOIN juegoplataforma jp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma 
        INNER JOIN juego j ON jp.idjuego = j.idjuego 
        WHERE ujp.idUsuario = " . $user->id . "";


if (isset($_COOKIE[$session_mod . "world"])) {
    $world = $_COOKIE[$session_mod . "world"];
    $sql .= " AND j.name LIKE '%" . $world . "%'";

    $sql_paging .= " AND j.name LIKE '%" . $world . "%'";
}

if (isset($_COOKIE[$session_mod."titulo"])) {
    $sql .= " AND j.name LIKE '%" . $_COOKIE[$session_mod."titulo"] . "%' ";
    $sql_paging .= " AND j.name LIKE '%" . $_COOKIE[$session_mod."titulo"] . "%' ";
}
$sql .= " ORDER BY " . $order . " " . $order2 . " Limit 12";

$sql_paging .= " ORDER BY " . $order . " " . $order2 . "";

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
        $next = $row["idUsuarioJuegoPlataforma"];
        if ($count == 0) {
            $ant = $row["idUsuarioJuegoPlataforma"];
        }
        ?>
        <div class="item_juegos col-sm-6 col-md-3 col-lg-2" id="item-<?= $row["idUsuarioJuegoPlataforma"] ?>" onclick="location.href = '<?= $url ?>/perfil/juego/<?= $row["idUsuarioJuegoPlataforma"] ?>'"> 
            <div class="item_juegos_content row">
                <div class="plat_imgs_cont">
                    <img src="<?= ($row["local"] ? $url : "") . $row["logo"] ?>" class="plat_img" >
                    <img src="<?= $url . "/imagenes/dist_logos/" . $row["dist_logo"] ?>" class="plat_img" style="float: right;">
                </div>
                <img src="<?= $row["url"] ?>" class="w-100 game_img" alt="...">
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