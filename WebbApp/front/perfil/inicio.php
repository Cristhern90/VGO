<?php
$sql = "
    SELECT p.name plataforma_name, p.logo, p.colorFondo, p.local, p.rondo, j.name titulo, d.name distribuidor, d.logo dist_logo, ujp.observacion, mj.url 
FROM usuariojuegoplataforma ujp 
INNER JOIN juegoplataforma jp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma 
INNER JOIN plataforma p ON jp.idPlataforma = p.idPlataforma 
INNER JOIN juego j ON jp.idjuego = j.idjuego 
INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
INNER JOIN distribuidor d ON d.idDistribuidor = ujp.idDistribuidor 
WHERE ujp.idUsuario = " . $user->id . " 
ORDER BY ujp.fechaAdquisicion DESC LIMIT 10
";
$sql_par = "
    SELECT par.observacion par_obs, par.fechaInicio, p.name plataforma_name, p.logo, p.colorFondo, p.local, p.rondo, j.name titulo, d.name distribuidor, d.logo dist_logo, 
    ujp.observacion, mj.url, (
	SELECT tj.fechaInicio 
    FROM tiempojuego tj
    WHERE tj.idPartida = par.idPartida
    ORDER BY fechaInicio DESC LIMIT 1
    ) last_play, ujp.idUsuarioJuegoPlataforma  
    FROM partida par
    INNER JOIN usuariojuegoplataforma ujp ON ujp.idUsuarioJuegoPlataforma = par.idUsuarioJuegoPlataforma
    INNER JOIN juegoplataforma jp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma 
    INNER JOIN plataforma p ON jp.idPlataforma = p.idPlataforma 
    INNER JOIN juego j ON jp.idjuego = j.idjuego 
    INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
    INNER JOIN distribuidor d ON d.idDistribuidor = ujp.idDistribuidor 
    WHERE ujp.idUsuario = " . $user->id . "  AND par.fechaFin is NULL
    ORDER BY last_play DESC LIMIT 10
";
$sql_par_fin = "
    SELECT par.observacion par_obs, par.fechaInicio, p.name plataforma_name, p.logo, p.colorFondo, p.local, p.rondo, j.name titulo, d.name distribuidor, d.logo dist_logo, ujp.observacion, mj.url 
FROM partida par
INNER JOIN usuariojuegoplataforma ujp ON ujp.idUsuarioJuegoPlataforma = par.idUsuarioJuegoPlataforma
INNER JOIN juegoplataforma jp ON ujp.idJuegoPlataforma = jp.idJuegoPlataforma 
INNER JOIN plataforma p ON jp.idPlataforma = p.idPlataforma 
INNER JOIN juego j ON jp.idjuego = j.idjuego 
INNER JOIN mediajuego mj ON mj.idJuego = j.idJuego AND tipo = 'cover'
INNER JOIN distribuidor d ON d.idDistribuidor = ujp.idDistribuidor 
WHERE ujp.idUsuario = " . $user->id . " AND par.fechaFin is not NULL
ORDER BY par.fechaInicio DESC LIMIT 10
";
?>
<h1>Perfil de <?= $user->name ?></h1>
<h2>Últimos juegos añadidos por <?= $user->name ?> <a href="<?= $url ?>/perfil/mis_juegos" style="font-size: 0.5em;">[ver todos]</a></h2>
<div class="slider last_released">
    <?php
    $query = get_query($sql);
    while ($row = $query->fetch_assoc()) {
        ?>
        <div class="item_slider"> 
            <div class="item_slider_content row">
                <div class="plat_imgs_cont">
                    <img src="<?= ($row["local"] ? $url : "") . $row["logo"] ?>" class="plat_img <?= ($row["rondo"] ? "rondo1" : "") ?>" style="background: <?= ($row["colorFondo"] ? $row["colorFondo"] : "none") ?>;">
                    <img src="<?= $url . "/imagenes/dist_logos/" . $row["dist_logo"] ?>" class="plat_img"  style="float: right;">
                </div>
                <img src="<?= $row["url"] ?>" class="w-100 game_img" alt="...">
                <!--<p class="text-center"><?= $row["titulo"] ?> de <?= $row["plataforma_name"] ?> con <?= $row["distribuidor"] ?></p>-->
            </div>
        </div>
    <?php } ?>
</div>
<h2>Últimos juegos terminados por <?= $user->name ?> <a href="" style="font-size: 0.5em;">[ver todos]</a></h2>
<div class="slider last_released">
    <?php
    $query_par_fin = get_query($sql_par_fin);
    while ($row = $query_par_fin->fetch_assoc()) {
        ?>
        <div class="item_slider"> 
            <div class="item_slider_content row">
                <div class="plat_imgs_cont">
                    <img src="<?= ($row["local"] ? $url : "") . $row["logo"] ?>" class="plat_img" >
                </div>
                <img src="<?= $row["url"] ?>" class="w-100 game_img" alt="...">

                <p class="text-center"><?= ($row["par_obs"] ? $row["par_obs"] : "-") ?></p>
                <!--<p class="text-center"><?= $row["titulo"] ?> en <?= $row["plataforma_name"] ?> mediante <?= $row["distribuidor"] ?></p>-->
            </div>
        </div>
    <?php } ?>
</div>

<script>
    $(document).ready(function () {
        $('.last_added').slick({infinite: false, speed: 300, slidesToShow: 5, slidesToScroll: 1,
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
        $('.last_released').slick({infinite: false, speed: 300, slidesToShow: 5, slidesToScroll: 1,
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