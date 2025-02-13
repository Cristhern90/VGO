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

    public $con; //variable to save BBDD connection

    public function __construct() {
        $this->con = new mysqli("localhost", "root", "", "vgord");
    }

    private function sql_prepare($query, $values = false) {
        $stmt = $this->con->prepare($query);

        if ($values) {
            foreach ($values as $key => $value) {
                $stmt->bind_param("s", $value); //asign value a ?
            }
        }

        $stmt->execute(); //execute query
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
                $query .= " AND ";
            }
            $operator = "=";
            $sentence .= $key . " " . $operator . " ?";
            array_push($prepare_values, $val);
            $count_w++;
        }

        return array("query" => $sentence, "values" => $prepare_values);
    }
}
