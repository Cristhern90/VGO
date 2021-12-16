<?php
$sql = "SELECT p.* 
        FROM plataforma p 
        WHERE p.idPlataforma = " . $_GET["id"];

$sql_assign = "SELECT COUNT(*) count FROM usuarioplataforma WHERE idPlataforma = " . $_GET["id"] . " AND idUsuario = " . $user->id;


$query = get_query($sql);

$query_assign = get_query($sql_assign);


$row = $query->fetch_assoc();

$row_assign = $query_assign->fetch_assoc();
?>

<div class="row" style="margin-bottom: 25px">
    <div class="col-sm-12 col-md-9">
        <h1><?= $row["name"] ?> 
            <?php if ($row_assign["count"]) { ?>
                <button onclick="conenct_post_arr('user', 'unlink_plat', ['<?= $_GET["id"] ?>', '<?= $user->id ?>'])" class="rondo2 red_but" title="adquirir"><i class='fas fa-unlink'></i></button>
            <?php } else { ?>
                <button onclick="conenct_post_arr('user', 'link_plat', ['<?= $_GET["id"] ?>', '<?= $user->id ?>'])" class="rondo2 cyan_but" title="adquirir"><i class='fas fa-link'></i></button>
            <?php } ?>
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
        SELECT j.*, mj.url, (
            SELECT GROUP_CONCAT(DISTINCT CONCAT(p.logo, ',',p.colorFondo, ',', p.local, ',', p.rondo) SEPARATOR '|') 
            FROM plataforma p
            INNER JOIN juegoplataforma jp ON jp.idPlataforma = p.idPlataforma
            WHERE jp.idJuego = j.idJuego
            ORDER BY fechaEstreno DESC
        ) logos 
        FROM juego j
        INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'";

$sql .= " INNER JOIN juegoplataforma jp ON jp.idJuego = j.idJuego AND jp.idPlataforma = " . $_GET["id"] . " AND (region = 8 OR region = 5) ";


$sql .= " WHERE 1 = 1";

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
        ?>
        <div class = "item_juegos col-sm-6 col-md-3 col-lg-2" id = "item-<?= $row["idJuego"] ?>" onclick = "location.href = '<?= $url ?>/juego/<?= $row["idJuego"] ?>'">
            <div class = "item_juegos_content row">
                <div class = "plat_imgs_cont">
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
