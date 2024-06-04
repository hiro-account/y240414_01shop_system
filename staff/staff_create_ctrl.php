<?php
require_once './staff_func.php';

function get_content() {
    return get_tbl_elem(array('last_name' => "氏", 'first_name' => "名", 'last_name_kana' => "", 'first_name_kana' => ""));
}

?>
