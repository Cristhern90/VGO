<?php
$order = "";
$session_mod = "user_plat_";
$idname = "idUsuarioPlataforma";
include './includes/list_ini.php';

?>
<div class="row">
    <div class="col-sm-6">
        <h2>Mis plataformas: <span id="count"></span>
            <button onclick="location.href = '<?=$url?>/plataformas'" class="rondo2 cyan_but"><i class='fas fa-plus'></i></button>
        </h2>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        Ordenar por: 
        <select onchange="conenct_post('platform', 'set_order1', $(this).val())">
            <option value="name" <?= ($order == "name" ? "selected" : "") ?>>Nombre</option>
        </select>
        <select onchange="conenct_post('platform', 'set_order2', $(this).val())">
            <option value="ASC" <?= ($order2 == "ASC" ? "selected" : "") ?>>ASC</option>
            <option value="DESC" <?= ($order2 == "DESC" ? "selected" : "") ?>>DESC</option>
        </select>
    </div>
</div>
<?php

$sql = "SELECT * FROM plataforma p";
$sql = "SELECT * FROM plataforma p
        INNER JOIN usuarioplataforma up on up.idPlataforma = p.idPlataforma 
        WHERE up.idUsuario = ".$user->id;

if (isset($_COOKIE[$session_mod . "limit"])) {
    $sql .= " WHERE " . $order . " " . ($order2 == "ASC" ? ">" : "<") . " '" . $limit . "'";
}

$sql_paging = "
        SELECT p.*  
        FROM plataforma p 
        INNER JOIN usuarioplataforma up on up.idPlataforma = p.idPlataforma 
        WHERE up.idUsuario = ".$user->id;

$sql .= " ORDER BY " . $order . " " . $order2 . " Limit 12";
$sql_paging .= " ORDER BY " . $order . " " . $order2 . "";

//echo $sql;

$query = get_query($sql);
$query_paging = get_query($sql_paging);

//include './includes/pagination2.php';
?>
<div class="row">
    <?php
    $count = 0;
    $next = "";
    $ant = "";
    while ($row = $query->fetch_assoc()) {
        $next = $row[$idname];
        if ($count == 0) {
            $ant = $row[$idname];
        }
        ?>
        <div class="item_plataformas col-sm-6 col-md-3 col-lg-2" id="item-<?= $row["idPlataforma"] ?>" onclick="location.href = '<?=$url?>/perfil/mi_plataforma/<?= $row["idUsuarioPlataforma"] ?>'"> 
            <div class="item_plataformas_content row">
                <div class="img_plat_cont">
                    <?php if ($row["logo"]) { ?>
                        <div class="plat_img">
                            <img src="<?= ($row["local"] ? $url : "") . $row["logo"] ?>" class="w-100 " alt="...">
                            <p style="font-weight: 600; font-size: 22px;margin: 0;"><?= $row["name"] ?></p>
                        </div>
                    <?php } else { ?>
                        <p style="font-weight: 600; font-size: 22px;margin: 0;"><?= $row["name"] ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php
include './includes/pagination.php';
?>