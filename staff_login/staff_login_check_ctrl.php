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
        $mysqli = new mysqli('localhost', 'root', '', 'y240608_01');

        if ($mysqli->connect_error) {
            return READ_FAILED;
        } else {
            $mysqli->set_charset('utf8');
        }

        $query =<<<EOQ
SELECT m.id AS id, pa.current AS current, pa.temporary AS temporary, pr.privilege AS privilege
 FROM m_staff_for_dev AS m
 INNER JOIN t_password_for_dev AS pa ON m.id = pa.id
 INNER JOIN t_privilege_for_dev AS pr ON m.id = pr.id
 INNER JOIN t_logical_delete_for_dev AS d ON m.id = d.id
 WHERE d.flag = FALSE AND m.id = ?
EOQ;
//         $query = 'select id from m_staff_for_dev';

        $id = NULL; $current = NULL; $temporary = NULL;  $privilege = NULL;

        $staff_id = intval($prm_post[STAFF_ID]);

        if ($stmt = $mysqli->prepare($query)) {
            $stmt->bind_param('i', $staff_id);
            $stmt->execute();
            $stmt->bind_result($id, $current, $temporary, $privilege);
            $stmt->fetch();
            echo  $id . '-' . $current . '-' . $temporary . '-' . $privilege;
        } else {
            echo '(該当なし)';
        }




    } catch (Exception $e) {
    }



//     try {
//         $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT id, password FROM m_staff WHERE id=?',
//             array($prm_post[STAFF_ID]));
//         $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

//         if (!$mixed || !password_verify($prm_post[STAFF_PASS], $mixed['password'])) {
//             return add_p('スタッフIDとパスワードのどちらか、もしくは双方とも不正');
//         }
//     } catch (Exception $e) {
//         return add_p('ログイン失敗（システム障害発生）');
//     }

//     session_start();
//     $_SESSION[LOGIN] = LOGIN;
//     $_SESSION[STAFF_ID] = $mixed['id'];
//     header('Location: ../system/system_top.php');
}
?>
