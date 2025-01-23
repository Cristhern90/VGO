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
    
    public $result = array("responce"=>"","errorCode"=>0,"errorMessage"=>"","alert"=>0,"reload"=>0,"newLocation"=>"");//array to response ajax conection
    
}
