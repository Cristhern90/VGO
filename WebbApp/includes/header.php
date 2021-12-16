<?php
if(!isset($_GET["id"])){
    $_GET["id"] = "";
}
?>
<?php if (!isset($_COOKIE["user_id"])) { ?>
    <header>
        <nav class="row">
            <ul style="padding: 0;">
                <a href="<?= $url ?>/"><li class="col-md-1 <?= ($_GET["page"] == "inicio" ? "active" : "") ?>">Inicio</li></a>
                <a href="<?= $url ?>/juegos/"><li class="col-lg-2 <?= ($_GET["page"] == "juegos" ? "active" : "") ?>">Juegos</li></a>
                <a href="<?= $url ?>/series/"><li class="col-lg-2 <?= ($_GET["page"] == "series" ? "active" : "") ?>">Series</li></a>
                <a href="<?= $url ?>/plataformas/"><li class="col-lg-2 <?= ($_GET["page"] == "plataformas" ? "active" : "") ?>">Plataformas</li></a>
                <a href="<?= $url ?>/generos/"><li class="col-lg-2 <?= ($_GET["page"] == "generos" ? "active" : "") ?>">Generos</li></a>
                <!--<a href="<?= $url ?>/companias/"><li class="col-lg-2 <?= ($_GET["page"] == "companias" ? "active" : "") ?>">Companias</li></a>-->
                <li class="col-lg-2 other_nav <?= ($_GET["page"] != "perfil" && $_GET["page"] != "inicio" ? "active" : "") ?>">Empresas
                    <ul id="header_datos" class="sub_nav col-lg-2">
                        <a href="<?= $url ?>/companias/"><li class="col-lg-2 <?= ($_GET["page"] == "companias" ? "active" : "") ?>">Desarrolladoras</li></a>
                        <a href="<?= $url ?>/vendedores/"><li class="col-lg-2 <?= ($_GET["page"] == "vendedores" ? "active" : "") ?>"><li>Vendedores</li></a>
                    </ul>
                </li>
                <a href="<?= $url ?>/search/">
                    <li class="col-md-1 <?= ($_GET["page"] == "search" ? "active" : "") ?>">
                        <i class="fas fa-search"></i>
                    </li>
                </a>
                <!--<li class="col-md-1">Perfil</li>-->
            </ul>
        </nav>
    </header>
<?php } else { ?>
    <header>
        <nav class="row">
            <ul style="padding: 0;">
                <a href="<?= $url ?>/"><li class="col-lg-1 col-md-2 <?= ($_GET["page"]=="inicio" ? "active" : "") ?>">Inicio</li></a>
                <a href="<?= $url ?>/perfil/mis_juegos/"><li class="col-lg-2 col-md-3 <?= ($_GET["id"] == "mis_juegos" ? "active" : "") ?>">Mis Juegos</li></a>
                <a href="<?= $url ?>/perfil/mis_plataformas"><li class="col-lg-2 col-md-4 <?= ($_GET["id"] == "mis_plataformas" ? "active" : "") ?>">Mis plataformas</li></a>
                <a href="<?= $url ?>/perfil/estadisticas" class="header_link_2"><li class="col-lg-2 <?= ($_GET["id"] == "estadisticas" ? "active" : "") ?>">Mis estadisticas</li></a>
                <a href="<?= $url ?>/perfil/tops" class="header_link_2"><li class="col-lg-2 <?= ($_GET["id"] == "tops" ? "active" : "") ?>">Mis Tops</li></a>
                <li class="col-lg-2 other_nav <?= ($_GET["page"] != "perfil" && $_GET["page"] != "inicio" ? "active" : "") ?>">Datos
                    <ul id="header_datos" class="sub_nav col-lg-2">
                        <a href="<?= $url ?>/juegos/"><li class="col-lg-2 <?= ($_GET["page"] == "juegos" ? "active" : "") ?>">Juegos</li></a>
                        <a href="<?= $url ?>/series/"><li class="col-lg-2 <?= ($_GET["page"] == "series" ? "active" : "") ?>">Series</li></a>
                        <a href="<?= $url ?>/cronologias/"><li class="col-lg-2 <?= ($_GET["page"] == "cronologias" ? "active" : "") ?>">Cronologias</li></a>
                        <a href="<?= $url ?>/plataformas/"><li class="col-lg-2 <?= ($_GET["page"] == "plataformas" ? "active" : "") ?>">Plataformas</li></a>
                        <a href="<?= $url ?>/generos/"><li class="col-lg-2 <?= ($_GET["page"] == "generos" ? "active" : "") ?>">Generos</li></a>
                        <a href="<?= $url ?>/companias/"><li class="col-lg-2 <?= ($_GET["page"] == "companias" ? "active" : "") ?>">Desarrolladoras</li></a>
                        <a href="<?= $url ?>/vendedores/"><li class="col-lg-2 <?= ($_GET["page"] == "vendedores" ? "active" : "") ?>">Vendedores</li></a>
                    </ul>
                </li>
                <a href="<?= $url ?>/perfil/">
                    <li class="col-lg-1 col-md-1 <?= ($_GET["page"] == "perfil" && !isset($_GET["id"]) ? "active" : "") ?>" style="padding: 6px;">
                        <img class="header_logo" src="<?= $url ?>/imagenes/user_logos/<?= ($user->logo ? $user->logo : "perfil.jpg") ?>">
                    </li>
                </a>
            </ul>
        </nav>
    </header>
<?php } ?>