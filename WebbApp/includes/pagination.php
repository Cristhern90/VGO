<?php
if (!isset($_COOKIE[$session_mod . "page"])) {
    $_COOKIE[$session_mod . "page"] = 1;
}
?>
<div class="pagination" id="pagination">
    <div class="pagination_nums">
        <?php if ($_COOKIE[$session_mod . "page"] == 1) { ?>
            <a onclick="" class="<?= ($_COOKIE[$session_mod . "page"] == 1 ? "unactived" : "") ?>">  
            <?php } else { ?>
                <a onclick="conenct_post_arr('game', 'set_session_2', ['<?= $session_mod ?>page', <?= $_COOKIE[$session_mod . "page"] - 1 ?>, '<?= $session_mod ?>limit',<?= "'" . $limit . "'" ?>])" class="<?= ($_COOKIE[$session_mod . "page"] == 1 ? "unactived" : "") ?>">
                <?php } ?>
                <div class="pagination_item"><</div>
            </a>
            <a onclick="conenct_post_arr('game', 'set_session_2', ['<?= $session_mod ?>page', 1, '<?= $session_mod ?>limit',<?= "'" . addslashes($limit) . "'" ?>])" class="<?= ($_COOKIE[$session_mod . "page"] == 1 ? "unactived" : "") ?>">
                <div class="pagination_item">1</div>
            </a>
            <?php
            $count = 1;
            $count8 = 2;
            $limit_0 = '';
//            if ($count8 > 12) {
            while ($row_paging = $query_paging->fetch_assoc()) {
                if ($count % 12 == 0) {
                    $num = 3;
                    if ($_COOKIE[$session_mod . "page"] >= 2) {
                        $num = 4;
                    }
                    if ($_COOKIE[$session_mod . "page"] == 3) {
                        $num = 5;
                    }
                    if ($_COOKIE[$session_mod . "page"] == 4) {
                        $num = 6;
                    }
                    $limit = addslashes($row_paging[str_replace("j.", "", $order)]);
                    if ($count8 == $num) {
                        ?>
                        <a data-page="<?= $count8 ?>"  class="unactived">
                            <div class="pagination_item nothing">...</div>
                        </a>
                    <?php } ?>

                    <a data-page="<?= $count8 ?>" onclick="conenct_post_arr('game', 'set_session_2', ['<?= $session_mod ?>page', <?= $count8 ?>, '<?= $session_mod ?>limit', '<?= addslashes($row_paging[str_replace("j.", "", $order)]) ?>'])" class="<?= ($_COOKIE[$session_mod . "page"] == $count8 ? "unactived" : "") ?> link_page link_page_<?= $count8 ?>">
                        <div class="pagination_item"><?= ($count8) ?></div>
                    </a>
                    <?php
                    $val = $_COOKIE[$session_mod . "page"] * 12;
//                    echo $count . " --> " . $val . ": " . $limit . "<br>";
                    if ($count == $val) {
                        $limit_0 = $limit;
                    }
                    $count8++;
                }
                $count++;
            }
//            }
            ?>
            <?php if ($_COOKIE[$session_mod . "page"] == $count8 + 1) { ?>
                <a onclick="" class="<?= ($_COOKIE[$session_mod . "page"] == $count8 - 1 ? "unactived" : "") ?>">
                <?php } else { ?>
                    <a onclick="conenct_post_arr('game', 'set_session_2', ['<?= $session_mod ?>page', <?= $_COOKIE[$session_mod . "page"] + 1 ?>, '<?= $session_mod ?>limit',<?= "'" . $limit_0 . "'" ?>])" class="<?= ($_COOKIE[$session_mod . "page"] == $count8 - 1 ? "unactived" : "") ?>">
                    <?php } ?>
                    <div class="pagination_item">></div>
                </a>
                </div>
                </div>
                <script>
                    $("#count").text(<?= $count - 1 ?>);

                    //    $(".nothing").hide();
                    if (<?= $count8 ?> > 10) {
                        $(".link_page").hide();
                        $(".link_page_2").show();
                        $(".link_page_<?= $_COOKIE[$session_mod . "page"] - 1 ?>").show();
                        $(".link_page_<?= $_COOKIE[$session_mod . "page"] ?>").show();
                        $(".link_page_<?= $_COOKIE[$session_mod . "page"] + 1 ?>").show();
                        if (<?= $_COOKIE[$session_mod . "page"] ?> >= 5 && <?= $_COOKIE[$session_mod . "page"] ?> <= 13) {
                            $(".link_page_<?= $count8 - 3 ?>").show();
                            $(".link_page_<?= $count8 - 3 ?>").attr("onclick", "");
                            $(".link_page_<?= $count8 - 3 ?>").addClass("unactived");
                            $(".link_page_<?= $count8 - 3 ?> .pagination_item").text("...");
                        }

                        $(".link_page_<?= $count8 - 2 ?>").show();
                        $(".link_page_<?= $count8 - 1 ?>").show();
                    } else {
                        $(".nothing").hide();
                    }

                    var ancho = $("#pagination").width();
                    $("#pagination").css("margin-left", "calc((100% - " + ancho + "px)/2)");
                </script>