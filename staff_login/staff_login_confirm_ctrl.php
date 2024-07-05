<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once '../cmn/func.php';
// require_once $to_cmn . 'CmnPdo.php';
require_once $to_cmn . 'query.php';

//TODO:定数移動および定数んび改行コードを含めるべきか
const READ_FAILED = '<p>ログイン失敗（システム障害発生）</p>' . LF;
const WRONG_ID_OR_PASSWORD = '<p>スタッフIDとパスワードのどちらか、もしくは双方とも不正</p>' . LF;

const QUERY =<<<EOQ
SELECT m.id AS id, pa.current AS current, pa.temporary AS temporary, pr.privilege AS privilege
FROM m_staff_for_dev AS m
INNER JOIN t_password_for_dev AS pa ON m.id=pa.id
INNER JOIN t_privilege_for_dev AS pr ON m.id=pr.id
INNER JOIN t_logical_delete_for_dev AS d ON m.id=d.id
WHERE d.flag=FALSE AND m.id=
EOQ;

function get_content($prm_post) {
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 未入力の項目のチェック
    $empty_msg_arr = check_unenter_item(array(STAFF_ID => 'スタッフID', STAFF_PASS => 'パスワード'), $item_val_arr);

    if (strlen($empty_msg_arr) > 0) {
        // 未入力の項目がある場合
        return $empty_msg_arr;
    }

//     $cmn_pdo = new CmnPdo();
//     $cmn_pdo->prepare(QUERY . $prm_post[STAFF_ID]);
//     $result_array = $cmn_pdo->execute();
    $result_array = execute_query(QUERY . $prm_post[STAFF_ID]);

    if (isset($result_array[EXCEPTION])) {
        // システム障害が発生した場合
        return READ_FAILED;
    }

    //TODO:下記不要か
    if (!isset($result_array[ROW_COUNT]) || !$result_array[ROW_COUNT] === I_1) {
        // 戻り値が一件以外の場合
        return WRONG_ID_OR_PASSWORD;
    }

    $select_result = $result_array[STMT]->fetch();

    if (is_bf_change_temp_pswd($select_result['current'], $select_result['temporary']) && strcmp($prm_post[STAFF_PASS], $select_result['temporary']) === I_0) {
        // 仮パスワードでログインを試みた(初回ログインの)場合
        header('Location: ./staff_first_login.php?staff_id=' . $prm_post[STAFF_ID]);
    } else if(!password_verify($prm_post[STAFF_PASS], $select_result['current'])) {
        // パスワードが合致しない場合
        return WRONG_ID_OR_PASSWORD;
    } else {
        session_start();
        $_SESSION[LOGIN] = LOGIN;
        $_SESSION[STAFF_ID] = $select_result['id'];
        $_SESSION['staff_privilege'] = $select_result['privilege'];
        header('Location: ../system/system_top.php');
    }
}
?>
