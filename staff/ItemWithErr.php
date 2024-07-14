<?php
require_once './Item.php';

class ItemWithErr extends Item {
    private $err_value;
    private $err_msg;

    function __construct($prm_physical_name, $prm_logical_name, $prm_err_value, $prm_err_msg, $prm_value = NULL) {
        parent::__construct($prm_physical_name, $prm_logical_name, $prm_value);
        $this->err_value = $prm_err_value;
        $this->err_msg = $prm_err_msg;
    }

    function get_err_value() {
        return $this->err_value;
    }

    function get_err_msg() {
        return $this->err_msg;
    }
}

?>
