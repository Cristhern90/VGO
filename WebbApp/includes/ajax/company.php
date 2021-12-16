<?php

include './ajax.php';

class company extends ajax {

    public function show_logos(){
        $sql = "SELECT * FROM compania";
        $query = $this->get_query($sql);
        while ($row = $query->fetch_assoc()) {
            print_r($row);
        }
    }
    
    public function set_order1() {
        $order = $this->op["id"];
        $_SESSION["comp_order"] = $order;
        unset($_SESSION["comp_page"]);
        unset($_SESSION["comp_lmits"]);
        $this->result["reload"] = 1;
    }

    public function set_order2() {
        $order = $this->op["id"];
        $_SESSION["comp_order2"] = $order;
        unset($_SESSION["comp_page"]);
        unset($_SESSION["comp_lmits"]);
        $this->result["reload"] = 1;
    }

}

session_start(); //abre $_SESSION
$ajax = new company($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();
echo json_encode($ajax->result);
?>