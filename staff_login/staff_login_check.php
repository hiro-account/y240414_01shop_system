<?php
require_once '../cmn/const.php';
require_once '../cmn/func.php';

$header = ERR_HEADER . STAFF_LOGIN_CHECK . ERR_MSG;

$header = LOCATION . get_host_and_dir() . '/../cmn_err/err.php?from=' . STAFF_LOGIN_CHECK . ERR_MSG;

$posted_arr = convert_sp_chara_rtn_arr($_POST);

// 未入力の項目のチェック
$empty_msg_arr = check_unfilled_item_rtn_arr(array(STAFF_ID => 'スタッフID', STAFF_PASS => 'パスワード'), $posted_arr);

if (count($empty_msg_arr) > 0) {
    // 未入力の項目がある場合、エラーページに遷移する
    header($header . implode(DELIMITER, $empty_msg_arr));
    exit();
}

try {
    $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT id FROM mst_staff WHERE id=? AND password=?',
        array($posted_arr[STAFF_ID], md5($posted_arr[STAFF_PASS])));
    $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mixed) {
        header($header . 'スタッフIDとパスワードのどちらか、もしくは双方とも不正');
        exit();
    }
} catch (Exception $e) {
    header($header . 'システム障害発生中');
    exit();
}

session_start();
$_SESSION[LOGIN] = LOGIN;
$_SESSION[STAFF_ID] = $mixed['id'];
header('Location: ../system/system_top.php');
?>
