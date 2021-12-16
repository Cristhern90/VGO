<?php

session_start();
$url = '/VGO2';

if (isset($_COOKIE["user_id"])) {
//    setcookie("user_id", "4");
    $sql = "SELECT * FROM usuario WHERE idUsuario = '" . $_COOKIE["user_id"] . "'";
    if ($query = get_query($sql)) {
        if ($row = $query->fetch_assoc()) {
            $user = (object) array("id" => $row["idUsuario"], "name" => $row["nombreCompleto"], "mail" => $row["mail"], "logo" => $row["logo"], "editor" => $row["editor"]);
            setcookie("user_id", $row["idUsuario"], time() + 3600);
        }
    }
} else {

//    setcookie("user_id", "4");
}

function get_query($sql) {
    $mysqli = mysqli_connect("localhost", "root", "", "vgo");
    if (!$mysqli) {
        $this->result["error"] = 100;
        exit;
    }
    $query = mysqli_query($mysqli, $sql);
    return $query;
}

function get_region($region) {
    $str = "";
    switch ($region) {
        case 1:
            $str = "Europa";
            break;
        case 2:
            $str = "Norte America";
            break;
        case 3:
            $str = "Australia";
            break;
        case 4:
            $str = "Nueva Zelanda";
            break;
        case 5:
            $str = "Japon";
            break;
        case 6:
            $str = "China";
            break;
        case 7:
            $str = "Asia";
            break;
        case 8:
            $str = "Mundial";
            break;
    }
    return $str;
}

function get_stats($mensual, $primer_dia) {
    if ($mensual) {
        $first = date("Y-m-01", strtotime($primer_dia));
        $last = date("Y-m-t", strtotime($primer_dia));
        $last2 = date("Y-m-d", strtotime($last . " +1 days"));
    } else {
        $first = date('Y-m-d', strtotime($primer_dia));
        $last = date("Y-m-d", strtotime($first . " +6 days"));
        $last2 = date("Y-m-d", strtotime($first . " +7 days"));
    }

    $period = new DatePeriod(new DateTime($first), new DateInterval('P1D'), new DateTime($last2));
    $arr_hours = array();
    foreach ($period as $key => $value) {
        $arr_hours["Sin Partida"][$value->format('Y-m-d')] = 0;
    }

    $sql_0 = "SELECT p.observacion FROM partida p WHERE p.idUsuarioJuegoPlataforma = " . $_GET["id2"];
    $query_0 = get_query($sql_0);
    while ($row_0 = $query_0->fetch_assoc()) {
        foreach ($period as $key => $value) {
            $arr_hours[$row_0["observacion"]][$value->format('Y-m-d')] = 0;
        }
    }


    $sql = "SELECT tj.idPartida, p.observacion, DATE(tj.fechaInicio) dia, SUM(TIMESTAMPDIFF(SECOND, tj.fechaInicio, tj.fechaFin)) as dif
                FROM tiempojuego tj
                LEFT JOIN partida p ON tj.idPartida = p.idpartida
                WHERE DATE(tj.fechaInicio) >= DATE('" . $first . "') AND DATE(tj.fechaInicio) <= DATE('" . $last . "') AND tj.idUsuarioJuegoPlataforma = " . $_GET["id2"] . "
                GROUP BY tj.idPartida, DATE(tj.fechaInicio)";

//    echo $sql;
    $query = get_query($sql);
    while ($row = $query->fetch_assoc()) {
        if ($row["observacion"]) {
            $arr_hours[$row["observacion"]][$row["dia"]] = $row["dif"] / 60 / 60;
        } else {
            $arr_hours["Sin Partida"][$row["dia"]] = $row["dif"] / 60 / 60;
        }
    }

    return $arr_hours;
}

function get_stats_gen($mensual, $primer_dia) {
    if ($mensual) {
        $first = date("Y-m-01", strtotime($primer_dia));
        $last = date("Y-m-t", strtotime($primer_dia));
        $last2 = date("Y-m-d", strtotime($last . " +1 days"));
    } else {
        $first = date('Y-m-d', strtotime($primer_dia));
        $last = date("Y-m-d", strtotime($first . " +6 days"));
        $last2 = date("Y-m-d", strtotime($first . " +7 days"));
    }

    $period = new DatePeriod(new DateTime($first), new DateInterval('P1D'), new DateTime($last2));
    $arr_hours = array();

    foreach ($period as $key => $value) {
        $arr_hours[0][$value->format('Y-m-d')] = 0;
    }

    $sql_0 = "SELECT idUsuarioJuegoPlataforma 
            FROM tiempojuego tj 
            WHERE fechaInicio >= '" . $first . " 00:00:00' AND fechaInicio <= '" . $last . " 23:59:59'
            Order by 1";
    $query_0 = get_query($sql_0);
    while ($row_0 = $query_0->fetch_assoc()) {
        foreach ($period as $key => $value) {
            $arr_hours[$row_0["idUsuarioJuegoPlataforma"]][$value->format('Y-m-d')] = 0;
        }
    }


    $sql = "SELECT tj.idUsuarioJuegoPlataforma, DATE(tj.fechaInicio) dia, SUM(TIMESTAMPDIFF(SECOND, tj.fechaInicio, tj.fechaFin)) as dif
            FROM tiempojuego tj 
            WHERE fechaInicio >= '" . $first . " 00:00:00' AND fechaInicio <= '" . $last . " 23:59:59'
            GROUP BY idUsuarioJuegoPlataforma, DATE(tj.fechaInicio)
            Order by 1";
//
//    echo $sql;
    $query = get_query($sql);
    while ($row = $query->fetch_assoc()) {
        $arr_hours[$row["idUsuarioJuegoPlataforma"]][$row["dia"]] = $row["dif"] / 60 / 60;
    }
//    print_r($arr_hours);
//
    return $arr_hours;
}

function get_stats_general($primer_dia, $agrupar, $show) {
    if ($show == "s") {
        $first = date('Y-m-d', strtotime($primer_dia));
        $last = date("Y-m-d", strtotime($first . " +6 days"));
        $last2 = date("Y-m-d", strtotime($first . " +7 days"));
    } else if ($show == "m") {
        $first = date('Y-m-01', strtotime($primer_dia));
        $last = date("Y-m-t", strtotime($primer_dia));
        $last2 = date("Y-m-d", strtotime($last . " +1 days"));
    } else if ($show == "a") {
        $first = date('Y-01-01', strtotime($primer_dia));
        $last = date("Y-12-31");
        $last2 = date("Y-m-d", strtotime($last . " +1 days"));
    }
    if ($agrupar == "d") {
        $period = new DatePeriod(new DateTime($first), new DateInterval('P1D'), new DateTime($last2));
    } else if ($agrupar == "s") {
        $period = new DatePeriod(new DateTime($first), new DateInterval('P7D'), new DateTime($last2));
    } else if ($agrupar == "m") {
        $period = new DatePeriod(new DateTime($first), new DateInterval('P1M'), new DateTime($last2));
    }
    $arr_hours = array();

    if ($agrupar == "s") {
        foreach ($period as $key => $value) {
            $arr_hours[0][$value->format('W')] = 0;
        }
    } else {
        foreach ($period as $key => $value) {
            $arr_hours[0][$value->format('Y-m-d')] = 0;
        }
    }


    $sql_0 = "SELECT idUsuarioJuegoPlataforma 
            FROM tiempojuego tj 
            WHERE fechaInicio >= '" . $first . " 00:00:00' AND fechaInicio <= '" . $last . " 23:59:59'
            Order by 1";
    $query_0 = get_query($sql_0);
    while ($row_0 = $query_0->fetch_assoc()) {
        foreach ($period as $key => $value) {
            if ($agrupar == "s") {
                $arr_hours[$row_0["idUsuarioJuegoPlataforma"]][$value->format('W')] = 0;
            } else {
                $arr_hours[$row_0["idUsuarioJuegoPlataforma"]][$value->format('Y-m-d')] = 0;
            }
        }
    }


    if ($agrupar == "d") {
        $sql = "SELECT tj.idUsuarioJuegoPlataforma, DATE(tj.fechaInicio) dia, SUM(TIMESTAMPDIFF(SECOND, tj.fechaInicio, tj.fechaFin)) as dif
            FROM tiempojuego tj 
            WHERE fechaInicio >= '" . $first . " 00:00:00' AND fechaInicio <= '" . $last . " 23:59:59'
            GROUP BY idUsuarioJuegoPlataforma, DATE(tj.fechaInicio)
            Order by 1";
    } else if ($agrupar == "s") {
        $sql = "SELECT tj.idUsuarioJuegoPlataforma, WEEK(tj.fechaInicio) dia, SUM(TIMESTAMPDIFF(SECOND, tj.fechaInicio, tj.fechaFin)) as dif
            FROM tiempojuego tj 
            WHERE fechaInicio >= '" . $first . " 00:00:00' AND fechaInicio <= '" . $last . " 23:59:59'
            GROUP BY idUsuarioJuegoPlataforma, WEEK(tj.fechaInicio)
            Order by 1";
    } else if ($agrupar == "m") {
        $sql = "SELECT tj.idUsuarioJuegoPlataforma, DATE(date_add(tj.fechaInicio,interval -DAY(tj.fechaInicio)+1 DAY)) dia, SUM(TIMESTAMPDIFF(SECOND, tj.fechaInicio, tj.fechaFin)) as dif
            FROM tiempojuego tj 
            WHERE fechaInicio >= '" . $first . " 00:00:00' AND fechaInicio <= '" . $last . " 23:59:59'
            GROUP BY idUsuarioJuegoPlataforma, DATE(date_add(tj.fechaInicio,interval -DAY(tj.fechaInicio)+1 DAY))
            Order by 1";
    }
//
//    echo $sql;
    $query = get_query($sql);
    while ($row = $query->fetch_assoc()) {
        if ($agrupar == "s") {
            $arr_hours[$row["idUsuarioJuegoPlataforma"]][$row["dia"]] = $row["dif"] / 60 / 60;
        } else {
            $arr_hours[$row["idUsuarioJuegoPlataforma"]][$row["dia"]] = $row["dif"] / 60 / 60;
        }
    }

    return $arr_hours;
}

function get_text_of_UJP($id) {
    $sql = "SELECT j.name j_name, p.name p_name, p.logo p_logo  
            FROM usuariojuegoplataforma ujp
            INNER JOIN juegoplataforma jp ON jp.idJuegoPlataforma = ujp.idJuegoPlataforma
            INNER JOIN juego j ON j.idJuego = jp.idJuego
            INNER JOIN plataforma p ON p.idPlataforma = jp.idPlataforma
            WHERE ujp.idUsuarioJuegoPlataforma = " . $id;
//    echo $sql;
    $query = get_query($sql);
    if ($row = $query->fetch_assoc()) {
        return $row;
    } else {
        return false;
    }
}

function get_color_trans($id, $trans) {
    switch ($id) {
        case 1:
            return 'rgba(255,0,0,' . $trans . ')';
        case 2:
            return 'rgba(0,255,0,' . $trans . ')';
        case 3:
            return 'rgba(0,0,255,' . $trans . ')';
        case 4:
            return 'rgba(255,255,0,' . $trans . ')';
        case 5:
            return 'rgba(255,0,255,' . $trans . ')';
        case 6:
            return 'rgba(0,255,255,' . $trans . ')';
        case 7:
            return 'rgba(125,0,0,' . $trans . ')';
        case 8:
            return 'rgba(0,125,0,' . $trans . ')';
        case 9:
            return 'rgba(0,0,125,' . $trans . ')';
        case 10:
            return 'rgba(125,125,0,' . $trans . ')';
        case 11:
            return 'rgba(125,0,125,' . $trans . ')';
        case 12:
            return 'rgba(0,125,125,' . $trans . ')';
        default:
            return 'rgba(0,0,0,' . $trans . ')';
            break;
    }
}

function get_dark_color_trans($id, $trans) {
    switch ($id) {
        case 1:
            return 'rgba(0,139,139,' . $trans . ')';
        default:
            return 'rgba(0,0,0,' . $trans . ')';
            break;
    }
}

?>