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
include './config/VGO.php';

class AJAX extends VGO {

    public $result = array("response" => "", "errorCode" => 0, "errorMessage" => "", "alert" => 0, "reload" => 0, "newLocation" => ""); //array to response ajax conection

    protected function insert($table, $dades) {//insert only one row
        $fields = array_keys($dades);
        $prepare_values = array_values($dades);

        if ($keys && $prepare_values) {
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

            if ($this->sql_prepare($query, $prepare_values)) {
                return true;
            } else {
                $this->result["errorCode"] = 3;
                return false;
            }
        } else {
            $this->result["errorCode"] = 3;
            $this->result["errorMessage"] = "Intento de insert sin valores o nombre de campo";
            return false;
        }
    }

    protected function update($table, $sets, $where) {
        $fields = array_keys($sets);
        $prepare_values = array_values($sets);
        if ($fields && $prepare_values && array_values($where)) {
            $query = "UPDATE " . $table . " SET ";
            foreach ($fields as $key => $field) {
                if ($key) {
                    $query .= ", ";
                }
                $query .= $field . " = ?";

                $where_sen = $this->formating_where($where);
                $query .= $where_sen["query"];
                array_merge($prepare_values, $where_sen["values"]);
            }

            if ($this->sql_prepare($query, $prepare_values)) {
                return true;
            } else {
                $this->result["errorCode"] = 3;
                return false;
            }
        } else {
            $this->result["errorCode"] = 3;
            $this->result["errorMessage"] = "Intento de update sin valores, nombre de campo o limitantes";
            return false;
        }
    }

    protected function delete($table, $where) {
        $fields = array_keys($where);
        $prepare_values = array_values($where);
        if ($fields && $prepare_values) {
            $query = "DELETE FROM " . $table . " ";

            $where_sen = $this->formating_where($where);
            $query .= $where_sen["query"];
            array_merge($prepare_values, $where_sen["values"]);
        } else {
            $this->result["errorCode"] = 3;
            $this->result["errorMessage"] = "Intento de update sin limitantes";
            return false;
        }
    }
}
