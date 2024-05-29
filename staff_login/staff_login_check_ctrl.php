<?php
require_once '../cmn/func.php';

function get_content($prm_post) {
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 未入力の項目のチェック
    $empty_msg_arr = check_unenter_item(array(STAFF_ID => 'スタッフID', STAFF_PASS => 'パスワード'), $item_val_arr);

    if (strlen($empty_msg_arr) > 0) {
        return $empty_msg_arr;
    }

    try {
        $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT id, password FROM mst_staff WHERE id=?',
            array($prm_post[STAFF_ID]));
        $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mixed || !password_verify($prm_post[STAFF_PASS], $mixed['password'])) {
            return add_p('スタッフIDとパスワードのどちらか、もしくは双方とも不正');
        }
    } catch (Exception $e) {
        return add_p('登録失敗（システム障害発生）');
    }

    session_start();
    $_SESSION[LOGIN] = LOGIN;
    $_SESSION[STAFF_ID] = $mixed['id'];
    header('Location: ../system/system_top.php');
}
?>
