<?php



class Item {
    private $physical_name;
    private $value;
    private $logical_name;

    function __construct($prm_physical_name, $prm_logical_name, $prm_value = NULL) {
        $this->physical_name = $prm_physical_name;
        $this->logical_name = $prm_logical_name;
        $this->value = $prm_value;
    }

    function get_physical_name() {
        return $this->physical_name;
    }

    function get_value() {
        return $this->value;
    }

    function get_logical_name() {
        return $this->logical_name;
    }


}


?>
