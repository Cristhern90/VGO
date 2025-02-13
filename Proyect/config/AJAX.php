<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of AJAX
 *
 * @author Cristian
 */
include './VGO.php';

class AJAX extends VGO {

    public $result = array("responce" => "", "errorCode" => 0, "errorMessage" => "", "alert" => 0, "reload" => 0, "newLocation" => ""); //array to response ajax conection

    protected function insert($table, $dades) {//insert only one row
        $fields = array_keys($dades);
        $prepare_values = array_values($dades);

        if ($keys && $values) {
            $query = "INSERT INTO " . $table . " (";
            foreach ($fields as $key => $field) {
                if ($key) {
                    $query .= ", ";
                }
                $query .= $field;
            }
            $query .= ") VALUES (";
            foreach ($fields as $key => $field) {
                if ($key) {
                    $query .= ", ";
                }
                $query .= "?";
            }
            $query .= ")";

            if ($stmt = $this->sql_prepare($query, $prepare_values)) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->result["errorCode"] = 1;
            $this->result["errorMessage"] = "Intento de insert sin valores o nombre de campo";
            return false;
        }
    }
}
