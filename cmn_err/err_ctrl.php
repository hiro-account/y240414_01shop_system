<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'const.php';
require_once $to_cmn . 'func.php';

const STAFF_ADD_CHECK = 'staff_add_check';

function  get_content($prm_get) {
    $content_arr = array();

    switch ($prm_get[FROM]) {
        case SYSTEM_TOP:
            $content_arr[H2] = 'メインメニュー';
            $content_arr[A] = A_STAFF_LOGIN;
            break;
        case STAFF_LOGIN_CHECK:
            $content_arr[H2] = S_STAFF . S_LOGIN;
            $content_arr[A] = A_HISTORY_BACK;
            break;
        case STAFF_ADD_CHECK:
            $content_arr[H2] = S_STAFF . S_TOROKU;
            $content_arr[A] = A_HISTORY_BACK;
            break;
        default:
            break;
    }

    $content_arr[ERR_MSG] = NULL;
    $err_msg_arr = explode(DELIMITER, $prm_get[ERR_MSG]);

    for ($i = 0; $i < count($err_msg_arr); $i++) {
        $content_arr[ERR_MSG] .= add_p($err_msg_arr[$i]) . LF;
    }

    return $content_arr;
}
?>
