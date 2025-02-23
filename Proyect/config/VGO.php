<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of VGO
 *
 * @author Cristian
 */
class VGO {

    protected $data_sql = array("server" => "", "BBDD" => "", "user" => "", "pass" => "");
    protected $url_json = "";
    protected $con; //variable to save BBDD connection

    public function __construct() {
        $this->url_json_bbdd = "./config/dades/";
    }

    private function read_BBDD_Json($fileName) {
        $json_file = file_get_contents($fileName);
        $json_array = json_decode($json_file, true);

        foreach ($json_array as $key => $json) {
            $this->data_sql[$key] = $json;
        }
    }

    protected function sql_prepare($query, $values = false) {
//        echo $query;
        $this->read_BBDD_Json($this->url_json_bbdd . "BBDD.json");

        $this->con = new mysqli(
                $this->data_sql["server"],
                $this->data_sql["user"],
                $this->data_sql["pass"],
                $this->data_sql["BBDD"]
        );

        if ($this->con->connect_error) {
            die("Conexión fallida: " . $this->con->connect_error);
        }

        $stmt = $this->con->prepare($query);
        if (!$stmt) {
            die("Error en prepare(): " . $this->con->error);
        }
        if ($values) {
            $types = "";
            $params = [];

            foreach ($values as &$value) { // ¡Importante: Pasar por referencia!
                if (is_int($value)) {
                    $types .= "i"; // Entero
                } elseif (is_double($value)) {
                    $types .= "d"; // Doble
                } elseif (is_string($value)) {
                    $types .= "s"; // String
                } else {
                    $types .= "b"; // Blob u otro tipo binario
                }
                $params[] = &$value; // Guardar referencia
            }

            array_unshift($params, $types); // Agregar tipos al inicio del array
            call_user_func_array([$stmt, 'bind_param'], $params);
        }

        if (!$stmt->execute()) {
            die("Error en execute(): " . $stmt->error);
        }

        return $stmt;
    }

    public function select($table, $values = "*", $join = "", $where = array(), $group = false, $order = "1") {
        $prepare_values = array();

        //Select values and from
        $query = "SELECT " . $values . " FROM " . $table;

        //joins
        if ($join) {
            $query .= " " . $join;
        }

        //where
        if ($where) {
            $where_sen = $this->formating_where($where);
            $query .= $where_sen["query"];
            $prepare_values = $where_sen["values"];
        }

        //group
        if ($group) {
            $query .= " GROUP BY " . $group;
        }
        //order
        $query .= " ORDER BY " . $order;

        //prepare query
        $stmt = $this->sql_prepare($query, $prepare_values);

        //get resul
        $result = $stmt->get_result();

        //save result in array
        $response = $result->fetch_all(MYSQLI_ASSOC);

        return $response;
    }

    protected function formating_where($where) {
        $prepare_values = array();
        $sentence = " WHERE ";
        $count_w = 0;
        foreach ($where as $key => $val) {
            if ($count_w) {
                $sentence .= " AND ";
            }
            $operator = "=";
            $sentence .= $key . " " . $operator . " ?";
            array_push($prepare_values, $val);
            $count_w++;
        }

        return array("query" => $sentence, "values" => $prepare_values);
    }
}
