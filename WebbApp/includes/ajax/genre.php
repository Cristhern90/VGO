<?php

include './ajax.php';

class genre extends ajax {
    
    public function set_order1() {
        $order = $this->op["id"];
        $_SESSION["gen_order"] = $order;
        unset($_SESSION["gen_page"]);
        unset($_SESSION["gen_lmits"]);
        $this->result["reload"] = 1;
    }

    public function set_order2() {
        $order = $this->op["id"];
        $_SESSION["gen_order2"] = $order;
        unset($_SESSION["gen_page"]);
        unset($_SESSION["gen_lmits"]);
        $this->result["reload"] = 1;
    }

}

session_start(); //abre $_SESSION
$ajax = new genre($_POST, $_SESSION);
$function = $_POST["funcion"];
$ajax->$function();
echo json_encode($ajax->result);
?>