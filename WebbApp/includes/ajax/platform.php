<?php

include './ajax.php';

class platform extends ajax {

    public function add_10_plats() {
        $ids = $this->existing_plats_ids();
        $where = "where ";
        $count_ids = 0;
        foreach ($ids as $value) {
            if ($count_ids) {
                $where .= " & ";
            }
            $where .= "id != " . $value;
            $count_ids++;
        }
        $where .= ";";
        $query = 'fields name,platform_logo.url;' . $where;

        $response1 = $this->API_conection('platforms', $query);

        $response = json_decode($response1);

        $insert_sql = "INSERT INTO plataforma (idPlataforma, name, logo) VALUES ";

        $count = 0;
        $count_i = 0;
        $count_u = 0;
        foreach ($response as $value) {
            $id = $value->id;
            $name = $value->name;
            $logo = "";
            $coma = "";
            if ($count > 0) {
                $coma = ",";
            }
            if (property_exists($value, "platform_logo")) {
                $logo = "'" . $value->platform_logo->url . "'";
                $logo2 = $value->platform_logo->url;
            } else {
                $logo = "NULL";
                $logo2 = "";
            }

            $insert_sql .= $coma . "(" . $id . ",'" . $name . "'," . $logo . ")";
            $count++;
        }

        $query = $this->get_query($insert_sql);


        curl_close($curl);

        $this->result["success"] = 1;
        $this->result["str"] = $count_i . " plataforma/s insertada/s, " . $count_u . " plataforma/s actualizada/s";
    }

    public function act_plats($id) {
        $ids = $this->existing_plats_ids();
        $where = "where id = " . $id . ";";
        $query = 'fields name,platform_logo.url;' . $where;

        $response1 = $this->API_conection('platforms', $query);

        $response = json_decode($response1);

        $insert_sql = "INSERT INTO plataforma (idPlataforma, name, logo) VALUES ";

        $count = 0;
        $count_i = 0;
        $count_u = 0;
        foreach ($response as $value) {
            $id = $value->id;
            $name = $value->name;
            $logo = "";
            $coma = "";
            if ($count > 0) {
                $coma = ",";
            }
            if (property_exists($value, "platform_logo")) {
                $logo = "'" . $value->platform_logo->url . "'";
                $logo2 = $value->platform_logo->url;
            } else {
                $logo = "NULL";
                $logo2 = "";
            }

            $row = $this->exists_plat($id);
            if ($row) {
                if ($row["name"] !== $name || $row["logo"] !== $logo2) {
                    $this->update_plat($id, $name, $logo2);
                    $count_u++;
                }
            } else {
                $insert_sql .= $coma . "(" . $id . ",'" . $name . "'," . $logo . ")";
                $count_i++;
            }
            $count++;
        }

        if ($count_i) {
            $query = $this->get_query($insert_sql);
        }


        $this->result["success"] = 1;
        $this->result["str"] = $count_i . " plataforma/s insertada/s, " . $count_u . " plataforma/s actualizada/s";
    }

    public function exists_plat($id) {
        $sql = "SELECT * FROM plataforma where idPlataforma = " . $id;
        $query = $this->get_query($sql);
        if ($row = $query->fetch_assoc()) {
            return $row;
        } else {
            return false;
        }
    }

    public function existing_plats_ids() {
        $sql = "SELECT * FROM plataforma";
        $query = $this->get_query($sql);
        $ids = array();
        while ($row = $query->fetch_assoc()) {
            array_push($ids, $row["idPlataforma"]);
        }
        return $ids;
    }

    public function update_plat($id, $name, $logo) {
        $sql = "UPDATE plataforma SET name = '" . $name . "', logo = '" . $logo . "' WHERE idPlataforma = " . $id;
        echo $sql . "<br>";
        $this->get_query($sql);
    }

    public function set_order1() {
        $order = $this->op["id"];
        $_SESSION["plat_order"] = $order;
        unset($_SESSION["plat_page"]);
        unset($_SESSION["plat_lmits"]);
        $this->result["location"] = $this->url . "/plataformas/";
    }

    public function set_order2() {
        $order = $this->op["id"];
        $_SESSION["plat_order2"] = $order;
        unset($_SESSION["plat_page"]);
        unset($_SESSION["plat_lmits"]);
        $this->result["location"] = $this->url . "/plataformas/";
    }
    

}

session_start(); //abre $_SESSION
$ajax = new platform($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();
echo json_encode($ajax->result);
?>