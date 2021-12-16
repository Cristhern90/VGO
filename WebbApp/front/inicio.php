<?php
$actual_link = 'http://' . $_SERVER['HTTP_HOST'];
//echo $actual_link;
//setcookie("TestCookie", "hola");
//echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";;
//print_r($_COOKIE);
//echo '<br>';
if (isset($_COOKIE["user_id"])) {

    $sql_play = "
SELECT j.name, (
    SELECT fechaInicio
    FROM tiempojuego tj 
    WHERE tj.idUsuarioJuegoPlataforma = ujp.idUsuarioJuegoPlataforma
    ORDER BY tj.fechaInicio DESC LIMIT 1
) last_play, mj.url, p.local, p.logo logo_plat, d.logo logo_dist, ujp.idUsuarioJuegoPlataforma
FROM juego j
INNER JOIN juegoplataforma jp ON jp.idJuego = j.idJuego
INNER JOIN usuariojuegoplataforma ujp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma
INNER JOIN mediajuego mj ON mj.idJuego = jp.idJuego AND mj.tipo = 'cover'
INNER JOIN plataforma p ON p.idPlataforma = jp.idPlataforma
INNER JOIN distribuidor d ON d.idDistribuidor = ujp.idDistribuidor
WHERE ujp.idUsuario = " . $user->id . "
ORDER BY last_play DESC LIMIT 10
";
}
$sql2 = "
        SELECT j.*, mj.url, (
            SELECT GROUP_CONCAT(DISTINCT CONCAT(p.logo, ',', p.colorFondo, ',', p.local, ',', p.rondo) SEPARATOR '|') 
            FROM plataforma p
            INNER JOIN juegoplataforma jp ON jp.idPlataforma = p.idPlataforma
            WHERE jp.idJuego = j.idJuego
            ORDER BY fechaEstreno DESC
        ) logos 
        FROM juego j
        INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
        ORDER BY PrimeraFechaEstreno DESC LIMIT 10";
?>
<div>
    <?php if (!isset($_COOKIE["user_id"])) { ?>
        <h2>Login</h2>
        <div style="min-height: 30vh;" class="row">
            <div class="col-md-12 col-sm-12  text-center">
                <p>No has iniciado sesión aún.</p>
                <p>Puedes iniciar sesion aquí</p>
                <form id="login">
                    <input name="funcion" type="hidden" value="login">
                    <label class="row">
                        <div class="col-md-12 col-lg-12">
                            Nombre de Usuario: 
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <input name="nombreUsuario" type="text">
                        </div>
                    </label>
                    <br>
                    <br>
                    <label class="row">
                        <div class="col-md-12 col-lg-12">
                            Contraseña: 
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <input name="pass1" type="password">
                        </div>
                    </label>
                    <br>
                    <div class="row text-center">

                        <div class="col-sm-12">
                            <input value="Login" type="submit" class="rondo3 cyan_but">
                        </div>
                    </div>
                    <br>
                    <p>Si no tienes cuenta puedes registrarte <a href="<?= $url ?>/signin">aquí</a></p>
                </form>
            </div>
            <div class="col-sm-6">
            </div>
        </div>
    <?php } else { ?>
        <div style="margin-bottom: 2em;">
            <h2>Ultimos partidas:</h2>
            <div class="slider last_played">
                <?php
                $query_play = get_query($sql_play);
                while ($row_play = $query_play->fetch_assoc()) {
                    ?>
                    <div class="item_slider col-sm-6 col-md-3 col-lg-2" id="item-<?= $row_play["idUsuarioJuegoPlataforma"] ?>" style="width:calc(95% - 10px);" onclick="location.href = '<?= $url ?>/perfil/juego/<?= $row_play["idUsuarioJuegoPlataforma"] ?>'"> 
                        <div class="item_slider_content row">
                            <img src="<?= $row_play["url"] ?>" class="w-100 game_img" alt="...">
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    $("#login").submit(function (e) {
        var myArray = new FormData($(this)[0]);
        conenction_post("user", myArray);
        e.preventDefault();
    });
</script>
<div style="margin-bottom: 100px;">
    <h2>Ultimos estrenos:</h2>
    <div class="slider last_released">
        <?php
        $query = get_query($sql2);
        while ($row = $query->fetch_assoc()) {
            ?>
            <div class="item_slider"> 
                <div class="item_slider_content row">
                    <div class="plat_imgs_cont">
                        <?php
                        $logos_arr = explode("|", $row["logos"]);
                        $count = 0;
                        foreach ($logos_arr as $key => $dats_logo) {
                            $logo_arr = explode(",", $dats_logo);
                            $logo = $logo_arr[0];
                            $fondo = $logo_arr[1];
                            $local = "";
                            if ($logo_arr[2]) {
                                $local = $url;
                            }

                            $rondo = $logo_arr[3];
                            if ($count <= 3) {
                                ?>
                                                                                                                                                                                                                    <!--<img src="<?= $logo ?>" class="plat_img col-4 <?= ($fondo ? "rondo" : "") ?>" style="left: calc(5px*<?= $count + 2 ?> + 17%*<?= $count ?>); <?= ($fondo ? "background-color:" . $fondo : "") ?>" >-->
                                <img src="<?= $local . $logo ?>" class="plat_img <?= ($fondo ? "rondo" . $rondo : "") ?>" style="left: calc(100%/6 * <?= $count ?>); <?= ($fondo ? "background-color:" . $fondo : "") ?>" >
                                <?php
                            }
                            $count++;
                        }
                        if (($count - 4) > 0) {
                            ?>
                            <img src="<?= $url ?>/imagenes/plat_logos/more.png" class="plat_img rondo1" style="left: calc(100%/6 * <?= $count ?>);" >
                        <?php } ?>
                    </div>
                    <img src="<?= $row["url"] ?>" class="w-100 game_img" alt="...">
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.last_played').slick({
            infinite: false, speed: 300, slidesToShow: 5, slidesToScroll: 1,dots:true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
        $('.last_released').slick({
            infinite: false, speed: 300, slidesToShow: 5, slidesToScroll: 1,dots: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>