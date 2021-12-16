<div class="item_element col-sm-6 col-md-3 col-lg-2" id="item-<?= $id ?>" onclick="location.href = '<?= $url ?>/<?= $page ?>/<?= $id ?>'"> 
    <div class="item_juegos_content row">
        <?php if ($page == "juegos") { ?>
        <?php } ?>
        <?php if ($page == "plataformas") { ?>
        <?php } ?>
        <?php if ($page == "generos") { ?>
        <?php } ?>
        <img src="<?= $row_url ?>" class="w-100 game_img" alt="...">
    </div>
</div>