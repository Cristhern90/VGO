<header>
    <div>
        <?php if ($user_id) { ?>
            <div>Mis Juegos</div>
            <div>Mis Partidas</div>
            <div>Mis Plataformas</div>
            <div>Mis Tops</div>
        <?php } ?>
        <div>Juegos</div>
        <div>Plataformas</div>
        <div>Series</div>
        <div>Generos</div>
        <div>Tiendas</div>
        <?php if ($user_id) { ?>
            <div>Perfil</div>
        <?php } ?>
        <div>Login</div>
    </div>
</header>