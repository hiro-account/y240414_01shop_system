<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'CmnPdo.php';

const CURRENT = 'current';

//TODO:定数移動および定数に改行コードを含めるべきか
const E_READ_FAILED = TS_P . S_LOGIN . S_SHIPPAI . S_SYSTEM_SHOGAI_HASSEI . TE_P . LF;
const E_WRONG_ID_OR_PASSWORD = TS_P. S_STAFF . U_ID .'と' . S_PASSWORD . 'のどちらか、もしくは双方とも' . S_FUSEI . TE_P . LF;

const QUERY =<<<EOQ
SELECT m.id AS id, pa.current AS current, pa.temporary AS temporary, pr.privilege AS privilege
FROM m_staff_for_dev AS m
INNER JOIN t_password_for_dev AS pa ON m.id = pa.id
INNER JOIN t_privilege_for_dev AS pr ON m.id = pr.id
INNER JOIN t_logical_delete_for_dev AS d ON m.id = d.id
WHERE d.flag = FALSE AND m.id = ?
EOQ;

function get_content($prm_post) {
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 未入力の項目のチェック
    $empty_msg_arr = check_unenter_item(array(STAFF_ID => S_STAFF . U_ID, STAFF_PASS => S_PASSWORD), $item_val_arr);

    if (isset($empty_msg_arr)) {
        // 未入力の項目がある場合
        return $empty_msg_arr;
    }

    $mixed_arr = array();

    try {
        $cmn_pdo = new CmnPdo();
        $stmt = $cmn_pdo->prepare(QUERY);
        $exe_result = $stmt->execute(array($prm_post[STAFF_ID])); //TODO:変数名変更

        while (TRUE) {
            $mixed = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($mixed == FALSE) {
                break;
            }

            $mixed_arr[] = $mixed;
        }
    } catch (Exception $e) {
        // システム障害が発生した場合
        return E_READ_FAILED;
    }

    if (!$exe_result) {
        //
        return E_READ_FAILED;
    }

    //TODO:下記不要か
    if (count($mixed_arr) !== I_1) {
        // 戻り値が一件以外の場合
        return E_WRONG_ID_OR_PASSWORD;
    }

    $mixed_0 = $mixed_arr[I_0];

    if (is_bf_change_temp_pswd($mixed_0[CURRENT], $mixed_0[TEMPORARY]) && strcmp($prm_post[STAFF_PASS], $mixed_0[TEMPORARY]) === I_0) {
        // 仮パスワードでログインを試みた(初回ログインの)場合
        //TODO:Locationの定数化、STAFF_ID→IDへの更新検討
        header(LOCATION . './staff_first_login.php?staff_id=' . $prm_post[STAFF_ID]);
    } else if(!isset($mixed_0[CURRENT]) || !password_verify($prm_post[STAFF_PASS], $mixed_0[CURRENT])) {
        // 仮パスワードがnullの場合、または、パスワードが合致しない場合
        return E_WRONG_ID_OR_PASSWORD;
    } else {
        session_start();
        $_SESSION[LOGIN] = LOGIN;
        $_SESSION[STAFF_ID] = $mixed_0[ID];
        $_SESSION[PRIVILEGE] = $mixed_0[PRIVILEGE];
        header(LOCATION . '../system/system_top.php');
    }
}
?>
