<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';

const A = 'a';


function get_content_arr_for_staff_login_err($prm_get) {
    $content_arr = array();
    $h2_and_err_msg = NULL;

    if (strcmp($prm_get[FROM], STAFF_LOGIN_CHECK) === I_0) {
        $h2_and_err_msg = '<h2>' . S_STAFF . S_LOGIN . '</h2>' . LF . $h2_and_err_msg;
        $content_arr[A] = A_HISTORY_BACK;
    } else {
        $content_arr[A] = A_STAFF_LOGIN;
    }

    $err_msg_arr = explode(DELIMITER, $prm_get[ERR_MSG]);

    for ($i = 0; $i < count($err_msg_arr); $i++) {
        $h2_and_err_msg .= add_p($err_msg_arr[$i]) . LF;
    }

    $content_arr[H2_AND_ERR_MSG] = $h2_and_err_msg;

    return $content_arr;
}
?>
