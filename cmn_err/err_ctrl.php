<?php
require_once('../cmn/const.php');
require_once('../cmn/func.php');

function  get_content_arr($prm_get) {
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

    $content_arr['err_tmp'] = null;
    $err_msg_arr = explode(DELIMITER, $_GET['err_msg']);

    for ($i = 0; $i < count($err_msg_arr); $i++) {
        $content_arr['err_tmp'] .= add_p($err_msg_arr[$i]) . LF;
    }

    return $content_arr;
}
?>
