<?php
$order = "";
$session_mod = "comp_";
if (!isset($_COOKIE[$session_mod . "order"])) {
    $order = "name";
} else {
    $order = $_COOKIE[$session_mod . "order"];
}
$order2 = "";
if (!isset($_COOKIE[$session_mod . "order2"])) {
    $order2 = "ASC";
} else {
    $order2 = $_COOKIE[$session_mod . "order2"];
}
$page = "";
if (!isset($_COOKIE[$session_mod . "page"])) {
    $page = 1;
} else {
    $page = $_COOKIE[$session_mod . "page"];
}

$limit = "";
if ($page == 1) {
    if ($order == "name") {
        if ($order2 == "ASC") {
            $limit = "";
        } else {
            $limit = "ZZZZZZZZZZ";
        }
    } else {
        if ($order2 == "ASC") {
            $limit = "1900-01-01";
        } else {
            $limit = "2900-01-01";
        }
    }
} else {
    $limit = $_COOKIE[$session_mod . "limit"];
}

$idname = "idCompania";
?>
<div class="row">
    <div class="col-sm-6">
        <h2>Plataformas:</h2>
    </div>
    <div class="col-sm-6" style="text-align: right;">
        Ordenar por: 
        <select onchange="conenct_post('company', 'set_order1', $(this).val())">
            <option value="name" <?= ($order == "name" ? "selected" : "") ?>>Nombre</option>
        </select>
        <select onchange="conenct_post('company', 'set_order2', $(this).val())">
            <option value="ASC" <?= ($order2 == "ASC" ? "selected" : "") ?>>ASC</option>
            <option value="DESC" <?= ($order2 == "DESC" ? "selected" : "") ?>>DESC</option>
        </select>
    </div>
</div>
<?php
$sql = "SELECT * FROM compania";

if (isset($_COOKIE[$session_mod . "limit"])) {
    $sql .= " WHERE " . $order . " ".($order2=="ASC"?">":"<")." '" . $limit . "'";
}
$sql .= " ORDER BY " . $order . " " . $order2 . " Limit 12";
//echo $sql;
$query = get_query($sql);

$sql_paging = "
        SELECT p.*  
        FROM compania p
        ORDER BY " . $order . " " . $order2 . "";
$query_paging = get_query($sql_paging);
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
        <div class="item_plataformas col-sm-6 col-md-3 col-lg-2" id="item-<?= $row[$idname] ?>"> 
            <div class="item_plataformas_content row">
                <div class="img_plat_cont">
                    <?php if ($row["logo"]) { ?>
                        <img src="<?= $row["logo"] ?>" class="w-100 plat_img" alt="...">  
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