<?php
if (!isset($_COOKIE["estad_desp"])) {
    $desp = 7;
} else {
    $desp = $_COOKIE["estad_desp"];
}
if (!isset($_COOKIE["lunes"])) {
    $lunes = date('Y-m-d', strtotime('monday this week'));
} else {
    $lunes = $_COOKIE["lunes"];
}
if (!isset($_COOKIE["estad_agrup"])) {
    $estad_agrup = "d";
} else {
    $estad_agrup = $_COOKIE["estad_agrup"];
}
if (!isset($_COOKIE["estad_show"])) {
    $estad_show = "s";
} else {
    $estad_show = $_COOKIE["estad_show"];
}

if (isset($_COOKIE["estad_show"])) {
    if ($_COOKIE["estad_show"] == "a") {
        $estad_agrup = "m";
    }
}
?>
<div class="row">
    <div class="col-sm-12">
        <h2>
            Estadisticas: Tiempo de juego
        </h2>
    </div>

    <div class="col-12 col-md-6">
        <button class="rondo cyan_but" onclick="conenct_post_arr('game', 'del_session', ['lunes'])">Hoy</button>
        <input type="date" class="datepicker" autocomplete="off" value="<?= $lunes ?>" onchange="conenct_post_arr('game', 'set_session', ['lunes', $(this).val()])">
    </div>
    <div class="col-12 col-md-6 text-md-end">
        Agrupar por <select onchange="conenct_post_arr('game', 'set_session', ['estad_agrup', $(this).val()])">
            <option value="d" <?= ($estad_agrup == "d" ? "selected" : "") ?>>Día</option>
            <option value="s" <?= ($estad_agrup == "s" ? "selected" : "") ?>>Semana</option>
            <option value="m" <?= ($estad_agrup == "m" ? "selected" : "") ?>>Mes</option>
        </select>
        Mostrar <select onchange="conenct_post_arr('game', 'set_session', ['estad_show', $(this).val()])">
            <option value="s" <?= ($estad_show == "d" ? "selected" : "") ?>>Semana</option>
            <option value="m" <?= ($estad_show == "m" ? "selected" : "") ?>>Mes</option>
            <option value="a" <?= ($estad_show == "a" ? "selected" : "") ?>>Año</option>
        </select>
    </div>
<!--    <div class="col-sm-12">
        <button class="rondo cyan_but" onclick="conenct_post_arr('game', 'del_session', ['lunes'])">Hoy</button>
        <input type="date" class="datepicker" autocomplete="off" value="<?= $lunes ?>" onchange="conenct_post_arr('game', 'set_session', ['lunes', $(this).val()])">
        <button class="rondo cyan_but <?= ($desp == 1 ? "active" : "") ?>" style="float: right;" onclick="conenct_post_arr('game', 'set_session', ['estad_desp', 1])">Mensual</button>
        <button class="rondo cyan_but <?= ($desp == 7 ? "active" : "") ?>" style="float: right;" onclick="conenct_post_arr('game', 'set_session', ['estad_desp', 7])">Semanal</button>

    </div>-->
    <div class="col-lg-1 col-md-2 sm-hide">
        <i class="fas fa-chevron-left change_stats l-50" onclick="conenct_post_arr('game', 'set_session', ['lunes', '<?= date('Y-m-d', strtotime(($desp == 7 ? $lunes . ' -' . $desp . " days" : date("Y-m-01", strtotime($lunes)) . " -1 month"))) ?>'])"></i>
    </div>
    <div class="col-lg-10 col-md-12"></div>

    <div class="col-lg-1 col-md-2 sm-hide">
        <i class="fas fa-chevron-right change_stats l-50" onclick="conenct_post_arr('game', 'set_session', ['lunes', '<?= date('Y-m-d', strtotime(($desp == 7 ? $lunes . ' +' . $desp . " days" : date("Y-m-01", strtotime($lunes)) . " -1 month"))) ?>'])"></i>
    </div>
    <div class="col-lg-12 col-md-12" style="margin-top: 2em;">
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>

<?php
if ($desp == 7) {
    $arr_hours = get_stats_gen(0, $lunes);
} else {
    $arr_hours = get_stats_gen(1, $lunes);
}

$arr_hours = get_stats_general($lunes, $estad_agrup, $estad_show);
if (isset(array_keys($arr_hours)[0])) {
    $label = array_keys($arr_hours)[0];
}
?>

<script>
    $(".datatable").DataTable();

    //chart js

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($arr_hours[$label])) ?>,
            datasets: [
<?php
$count = 0;
foreach ($arr_hours as $key => $value) {
    if (get_text_of_UJP($key)) {
        $bol = true;
        if ($bol) {
            if ($count) {
                echo ",";
            }
            echo "{";
            echo "  label: '" . addslashes(get_text_of_UJP($key)["j_name"]) . "',";
            echo "  data: " . json_encode(array_values($value)) . ",";
            echo "  backgroundColor: ['" . get_color_trans($count + 1, 0.5) . "'],";
            echo "  borderColor: ['" . get_color_trans($count + 1, 1) . "'],
        borderWidth: 1";
            echo "}";
            $count++;
        }
    }
}
?>]
        },
        options: {
            scales: {
                xAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                y: {
                    suggestedMax: 1,
                    ticks: {
                        stepSize: 0.25,
                        callback: function (value, index, values) {
                            var horas = value;
                            var horas_int = Math.trunc(horas);
                            var minutes = (horas - horas_int) * 60;
                            var minutes_int = Math.trunc(minutes);
                            return horas_int + "h:" + minutes_int + "m";
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            var label = context.dataset.label || '';

                            if (label) {
                                label += ': ';
                            }
                            var value = context.parsed.y;
                            var horas = value;
                            var horas_int = Math.trunc(horas);
                            var minutes = (horas - horas_int) * 60;
                            var minutes_int = Math.trunc(minutes);
                            return label + horas_int + "h:" + minutes_int + "m";
                        }
                    }
                }
            },
            ticks: {
                beginAtZero: true,
                max: 1,
                suggestedMin: 0
            }
        }
    });
</script>