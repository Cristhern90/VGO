<?php
$sql = "SELECT p.* 
        FROM plataforma p 
        INNER JOIN usuarioplataforma up ON up.idPlataforma = p.idPlataforma AND idUsuario = " . $user->id . " 
        WHERE up.idUsuarioPlataforma = " . $_GET["id2"];

//echo $sql;
$query = get_query($sql);


$row = $query->fetch_assoc();
?>

<div class="row" style="margin-bottom: 25px">
    <div class="col-sm-12 col-md-9">
        <h1><?= $row["name"] ?> 
            <button onclick="conenct_post_arr('user', 'unlink_plat', ['<?= $_GET["id"] ?>', '<?= $user->id ?>'])" class="rondo2 red_but" title="adquirir"><i class='fas fa-unlink'></i></button>
        </h1>
        <ul>
            <li>Color Fondo: <?= ($row["colorFondo"] ? '<input type="color" value="' . $row["colorFondo"] . '">' : "transparente") ?></li>
            <li>Logo redondeado: <?= ($row["rondo"] ? "Si" : "No") ?></li>
        </ul>
    </div>
    <div class="col-sm-12 col-md-3">
        <img src="<?= ($row["local"] ? $url : "") . $row["logo"] ?>" class="w-100" alt="...">
    </div>
</div>
<?php
$order = "";
$session_mod = "my_game_";
$idname = "idUsuarioJuegoPlataforma";

$sql = "
SELECT p.logo, p.colorFondo, p.local, p.rondo, j.name titulo, d.name distribuidor, d.logo dist_logo, ujp.observacion, mj.url, ujp.idUsuarioJuegoPlataforma 
FROM usuariojuegoplataforma ujp 
INNER JOIN juegoplataforma jp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma 
INNER JOIN plataforma p ON jp.idPlataforma = p.idPlataforma 
INNER JOIN juego j ON jp.idjuego = j.idjuego 
INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
INNER JOIN distribuidor d ON d.idDistribuidor = ujp.idDistribuidor 
INNER JOIN usuarioplataforma up ON up.idPlataforma = p.idPlataforma AND up.idUsuario = ujp.idUsuario AND up.idUsuarioPlataforma = " . $_GET["id2"] . " 
WHERE ujp.idUsuario = " . $user->id . "";

$sql .= " ORDER BY j.name";

$query = get_query($sql);
?>
<div class="row" style="margin-bottom: 25px">
    <h2>Juegos: <span id="count_games"></span></h2>
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

<script>
    $("#count_games").text("<?=$count?>");
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
