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

    private function sql_prepare($query, $values) {
        $stmt = $mysqli->prepare($query);

        if ($values) {
            foreach ($values as $key => $value) {
                $stmt->bind_param("s", $value);//asign value a ?
            }
        }
        
        $stmt->execute();//execute query
    }
}
