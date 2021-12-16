<div class="row">
    <div class="col-12 col-md-11" style="padding-right: 0;">
        <input name="titulo" type="text" id="search_by_title" style="margin: 10px 0;width: 100%;padding: 0.25em;font-size: 1.5em;" value="<?= (isset($_COOKIE[$session_mod."titulo"]) ? $_COOKIE[$session_mod."titulo"] : "") ?>">
    </div>
    <div class="col-12 col-md-1" style="padding-left: 0;">
        <input type="button" onclick="conenct_post_arr('game', 'set_session', ['<?=$session_mod?>titulo', $('#search_by_title').val()]);" value="Buscar" style="margin: 10px 0; width: 100%;font-size: 1.5em; padding: 0.25em;">
    </div>
</div>
<script>
    $("#search_by_title").on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            conenct_post_arr('game', 'set_session', ['<?=$session_mod?>titulo', $(this).val()]);
        }
    });
</script>

<hr>