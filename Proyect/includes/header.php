<header>
    <div>
        <?php if ($user_id) { ?>
            <div><a href="?page=game">Mis Juegos</a></div>
            <div>Mis Partidas</div>
            <div>Mis Plataformas</div>
            <div>Mis Tops</div>
        <?php } ?>
        <div><a href="?page=game">Juegos</a></div>
        <div><a href="?page=platform">Plataformas</a></div>
        <div><a href="?page=genre">Generos</a></div>
        <div><a href="?page=franchise">Franquicias</a></div>
        <div><a href="?page=collection">Series</a></div>
        <div>Tiendas</div>
        <?php if ($user_id) { ?>
            <div>Perfil</div>
        <?php } ?>
        <div>Login</div>
    </div>
</header>