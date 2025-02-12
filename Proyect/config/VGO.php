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
        $response = array();
        $query = "SELECT " . $values . " FROM " . $table;
        if ($where) {
            $query .= " WHERE ";
            $count_w = 0;
            foreach ($where as $key => $val) {
                if ($count_w) {
                    $query .= " AND ";
                }
                $operator = "=";
                $query .= $key . " " . $operator . " " . $val;
                $count_w++;
            }
        }
        echo $query . "<hr>";

        $stmt = $this->sql_prepare($query);

        $result = $stmt->get_result();

        $response = ($result->fetch_all(MYSQLI_ASSOC));

//        while ($stmt->fetch()) {
//            printf("id = %s (%s), label = %s (%s)\n", $out_id, gettype($out_id), $out_label, gettype($out_label));
//        }

        return $response;
    }
}
