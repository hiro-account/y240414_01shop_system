<?php
require_once('const.php');

function split_str_rtn_arr($prm_str, $prm_arr) {
    $start = I_0;
    $split_arr = array();

    for ($i = I_0; $i < count($prm_arr); $i++) {
        $length = $prm_arr[$i];
        $split_arr[] = substr($prm_str, $start, $length);
        $start += $length;
    }

    $split_arr[] = substr($prm_str, $start);

    return $split_arr;
}


?>
