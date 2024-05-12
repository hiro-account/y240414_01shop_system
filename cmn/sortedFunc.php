<?php
require_once('const.php');

function process_month_and_day($prm_str) {
    if (strcmp(substr($prm_str, I_0, I_1), '0') === 0) {
        return substr($prm_str, I_1);
    }

    return $prm_str;
}




//----------------------------------------
//TODO:以下不使用削除検討

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
