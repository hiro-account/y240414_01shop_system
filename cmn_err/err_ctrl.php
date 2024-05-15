<?php
require_once('../cmn/const.php');
require_once('../cmn/func.php');

function  get_content($prm_get) {
    $content_arr = array();

    switch ($prm_get[FROM]) {
        case SYSTEM_TOP:
            $content_arr[H2] = 'メインメニュー';
            $content_arr[A] = A_STAFF_LOGIN;
            break;
        case STAFF_LOGIN_CHECK:
            $content_arr[H2] = 'スタッフログイン';
            $content_arr[A] = A_HISTORY_BACK;
            break;
        case STAFF_ADD_CHECK:
            $content_arr[H2] = 'スタッフ登録';
            $content_arr[A] = A_HISTORY_BACK;
            break;
        default:
            break;
    }

    $content_arr['err_msg'] = NULL;
//     $err_msg_arr = explode(DELIMITER, $_GET['err_msg']);

    if (true);


    $err_msg_arr = explode(DELIMITER, $prm_get['err_msg']);

    for ($i = 0; $i < count($err_msg_arr); $i++) {
        $content_arr['err_msg'] .= add_p($err_msg_arr[$i]) . LF;
    }

    return $content_arr;
}


function get_content_arr_for_staff_login_err($prm_get) {
    $content_arr = array();
    $h2_and_err_msg = NULL;

    if (strcmp($prm_get[FROM], STAFF_LOGIN_CHECK) === I_0) {
        $h2_and_err_msg = '<h2>スタッフログイン</h2>' . LF . $h2_and_err_msg;
        $content_arr[A] = A_HISTORY_BACK;
    } else {
        $content_arr[A] = A_STAFF_LOGIN;
    }

    $err_msg_arr = explode(DELIMITER, $prm_get['err_msg']);

    for ($i = 0; $i < count($err_msg_arr); $i++) {
        $h2_and_err_msg .= add_p($err_msg_arr[$i]) . LF;
    }

    $content_arr['H2_AND_ERR_MSG'] = $h2_and_err_msg;

    return $content_arr;
}



?>
