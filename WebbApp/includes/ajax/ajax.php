<?php

class ajax {

    public $result = array("success" => "", "error" => 0, "location" => 0, "alert" => 0, "reload" => 0, "str" => "", "result" => "");
    public $op;
    public $session;
    public $url = "/VGO2/";
    private $CURLOPT_HTTPHEADER = array('Client-ID: [client id]', 'Authorization: Bearer [auto]', 'Content-Type: text/plain', 'Cookie: [cookie]');

    public function __construct($op, $session) {
        $this->op = $op;
        $this->session = $session;
    }

    public function get_query($sql) {
        $mysqli = mysqli_connect("localhost", "root", "", "vgo");
        if (!$mysqli) {
            $this->result["error"] = 100;
            exit;
        }
        $query = mysqli_query($mysqli, $sql);
        return $query;
    }

    public function API_conection($loc, $query) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.igdb.com/v4/' . $loc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $query,
            CURLOPT_HTTPHEADER => $this->CURLOPT_HTTPHEADER,
        ));
        $response = curl_exec($curl);
        return $response;
    }

    public function show_all_fields($page) {

        $query = 'fields *;';

        $response1 = $this->API_conection($page, $query);

        $response = json_decode($response1);

        foreach ($response as $key => $value_arr) {
            echo $key . ' => <br>';
            foreach ($value_arr as $key2 => $value) {
                echo " - " . $key2 . " => " . json_encode($value) . "<br>";
            }
            echo '<br>';
        }
    }

    //general

    public function insert($table, $array = array()) {
        $sql = "INSERT INTO " . $table;
        $names = "";
        $values = "";

        $count = 0;
        foreach ($array as $key => $value) {
            if ($count > 0) {
                $names .= ", ";
                $values .= ", ";
            }
            $names .= $key;
            $values .= $value;
            $count++;
        }
        $sql .= " (" . $names . ") VALUES (" . ($values) . ")";
//            echo $sql . "<br>";
        if ($query = $this->get_query($sql)) {
            return true;
        } else {
//            echo $sql . "<br>";
            $this->result["alert"] = 1;
            $this->result["str"] = $sql;
            return false;
        }
    }

    public function insert_test($table, $array = array()) {
        $sql = "INSERT INTO " . $table;
        $names = "";
        $values = "";

        $count = 0;
        foreach ($array as $key => $value) {
            if ($count > 0) {
                $names .= ", ";
                $values .= ", ";
            }
            $names .= $key;
            $values .= $value;
            $count++;
        }
        $sql .= " (" . $names . ") VALUES (" . ($values) . ")";
        return $sql;
    }

    public function update($table, $sets = array(), $where = array()) {
        $sql = "UPDATE " . $table . " SET ";

        $count = 0;
        foreach ($sets as $key => $value) {
            if ($count > 0) {
                $sql .= ", ";
            }
            $sql .= " " . $key . " = " . $value . " ";
            $count++;
        }

        $sql .= " WHERE ";

        $count = 0;
        foreach ($where as $key => $value) {
            if ($count > 0) {
                $sql .= " AND ";
            }
            if (is_array($value)) {
                $sql .= " " . $key . " " . $value[0] . " " . $value[1] . " ";
            } else {
                if ($value == "NULL") {
                    $sql .= " " . $key . " IS " . $value . " ";
                } else {
                    $sql .= " " . $key . " = " . $value . " ";
                }
            }
            $count++;
        }

//        echo $sql . "<br>";
        if ($query = $this->get_query($sql)) {
            return true;
        } else {
            echo $sql . "<br>";
            return false;
        }
    }

    public function delete($table, $arr_where) {
        $sql = "DELETE FROM " . $table . " WHERE ";

        $count = 0;
        foreach ($arr_where as $key => $value) {
            if ($count > 0) {
                $sql .= " AND ";
            }
            $sql .= " " . $key . " = " . $value . " ";
            $count++;
        }

//        echo $sql . "<br>";
        if ($query = $this->get_query($sql)) {
            return true;
        } else {
            echo $sql . "<br>";
            return false;
        }
    }
    
    public function select($table, $fields = "*", $arr_where = array(),$inner = false){
        $sql = "SELECT ".$fields." FROM ".$table." ";
        if($inner){
            $sql .= " ".$inner." ";
        }
        
        $count = 0;
        foreach ($arr_where as $key => $value) {
            if ($count > 0) {
                $sql .= " AND ";
            }else{
                $sql .= " WHERE ";
            }
            $sql .= " " . $key . " = " . $value . " ";
            $count++;
        }
//        echo $sql . "<br>";
        if ($query = $this->get_query($sql)) {
            return $query;
        } else {
            echo $sql . "<br>";
            return false;
        }
    }

    public function existing_ids($table) {
        $sql = "SELECT * FROM " . $table;
        $query = $this->get_query($sql);
        $ids = array();
        while ($row = $query->fetch_assoc()) {
            array_push($ids, $row["id" . ucfirst($table)]);
        }
        return $ids;
    }

    public function existing_id($table, $id_name, $id) {
        $sql = "SELECT COUNT(*) count FROM " . $table . " WHERE " . $id_name . " = " . $id;
        $query = $this->get_query($sql);
        $row = $query->fetch_assoc();
        return $row["count"];
    }

    public function existing_2_id($table, $id_name1, $id1, $id_name2, $id2) {
        $sql = "SELECT COUNT(*) count FROM " . $table . " WHERE " . $id_name1 . " = " . $id1 . " AND " . $id_name2 . " = " . $id2;
        $query = $this->get_query($sql);
        $row = $query->fetch_assoc();
        return $row["count"];
    }

    public function existing_3_id($table, $id_name1, $id1, $id_name2, $id2, $id_name3, $id3) {
        $sql = "SELECT COUNT(*) count FROM " . $table . " WHERE " . $id_name1 . " = " . $id1 . " AND " . $id_name2 . " = " . $id2 . " AND " . $id_name3 . " = " . $id3;
        $query = $this->get_query($sql);
        $row = $query->fetch_assoc();
        return $row["count"];
    }

    //plataforma
    public function existing_plats_ids() {
        $sql = "SELECT * FROM plataforma";
        $query = $this->get_query($sql);
        $ids = array();
        while ($row = $query->fetch_assoc()) {
            array_push($ids, $row["idPlataforma"]);
        }
        return $ids;
    }

    public function insert_plataforma($id, $name, $logo) {
        $logo = str_replace("t_thumb", "t_logo_med", $logo);
        $logo_bien = str_replace(".jpg", ".png", $logo);
        $sql = "INSERT INTO plataforma (idPlataforma, name, logo) VALUES (" . $id . ",'" . $name . "', '" . $logo_bien . "')";
        if ($query = $this->get_query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    //genero
    public function existing_genre_ids() {
        $sql = "SELECT * FROM genero";
        $query = $this->get_query($sql);
        $ids = array();
        while ($row = $query->fetch_assoc()) {
            array_push($ids, $row["idGenero"]);
        }
        return $ids;
    }

    public function insert_genero($id, $name) {
        $sql = "INSERT INTO genero (idGenero, name) VALUES (" . $id . ",'" . $name . "')";
        if ($query = $this->get_query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    //compania
    public function existing_compania_ids() {
        $sql = "SELECT * FROM compania";
        $query = $this->get_query($sql);
        $ids = array();
        while ($row = $query->fetch_assoc()) {
            array_push($ids, $row["idCompania"]);
        }
        return $ids;
    }

    public function insert_compania($id, $name, $logo) {
        $logo = str_replace("t_thumb", "t_logo_med", $logo);
        $logo_bien = str_replace(".jpg", ".png", $logo);
        $sql = "INSERT INTO compania (idCompania, name, logo) VALUES (" . $id . ",'" . $name . "', '" . $logo_bien . "')";
        if ($query = $this->get_query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function set_session() {
        $name = $this->op["id1"];
        $value = $this->op["id2"];
        $actual_link = $_SERVER['HTTP_HOST'];

        setcookie($name, $value, time() + 3600, "/");
        $this->result["reload"] = 1;
//        $this->result["alert"] = 1;
//        $this->result["msg"] = json_encode($_COOKIE);
    }

    public function del_session() {
        $name = $this->op["id1"];
        unset($_SESSION[$name]);
        $actual_link = $_SERVER['HTTP_HOST'];
        setcookie($name, "", time() - 3600, "/");
        $this->result["reload"] = 1;
    }

    public function set_session_2() {
        $name = $this->op["id1"];
        $value = $this->op["id2"];
        $name2 = $this->op["id3"];
        $value2 = $this->op["id4"];
        $actual_link = $_SERVER['HTTP_HOST'];
        setcookie($name, $value, time() + 3600, "/");
        setcookie($name2, $value2, time() + 3600, "/");
        $this->result["reload"] = 1;
//        $this->result["alert"] = 1;
//        $this->result["msg"] = $actual_link;
    }

    public function del_session_2() {
        $name = $this->op["id1"];
        $name2 = $this->op["id2"];
        $actual_link = $_SERVER['HTTP_HOST'];
        setcookie($name, "", time() - 3600, "/");
        setcookie($name2, "", time() - 3600, "/", $actual_links, 1);
        $this->result["reload"] = 1;
    }

    public function set_session_3() {
        $name = $this->op["id1"];
        $value = $this->op["id2"];
        $name2 = $this->op["id3"];
        $value2 = $this->op["id4"];
        $name3 = $this->op["id5"];
        $value3 = $this->op["id6"];
        $actual_link = $_SERVER['HTTP_HOST'];
        setcookie($name, $value, time() + 3600, "/");
        setcookie($name2, $value2, time() + 3600, "/");
        setcookie($name3, $value3, time() + 3600, "/");
        $this->result["reload"] = 1;
    }

    public function del_session_3() {
        $name = $this->op["id1"];
        $name2 = $this->op["id2"];
        $name3 = $this->op["id3"];
        $actual_link = $_SERVER['HTTP_HOST'];
        setcookie($name, "", time() - 3600, "/");
        setcookie($name2, "", time() - 3600, "/");
        setcookie($name3, "", time() - 3600, "/");
        $this->result["reload"] = 1;
    }

}

?>
