<?php
require_once '../cmn/func.php';

//TODO:定数移動および定数んび改行コードを含めるべきか
const READ_FAILED = '<p>ログイン失敗（システム障害発生）</p>';

function get_content($prm_post) {
    // mysqliのコンストラクタの例外用設定
    mysqli_report(MYSQLI_REPORT_STRICT);

    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 未入力の項目のチェック
    $empty_msg_arr = check_unenter_item(array(STAFF_ID => 'スタッフID', STAFF_PASS => 'パスワード'), $item_val_arr);

    if (strlen($empty_msg_arr) > 0) {
        return $empty_msg_arr;
    }

    $mysqli = NULL;

    $id = NULL;
    $current = NULL;
    $temporary = NULL;
    $privilege = NULL;

    try {
        $mysqli = new mysqli('localhost', 'root', '', 'y240608_01');

        if ($mysqli->connect_error) {
            return READ_FAILED . '1' . LF;
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

        $err_msg_elem = NULL;

        if ($stmt = $mysqli->prepare($query)) {
            $staff_id = intval($prm_post[STAFF_ID]);
            $stmt->bind_param('i', $staff_id);
            $stmt->execute();
            $stmt->bind_result($id, $current, $temporary, $privilege);

            if (!$stmt->fetch()) {
                $err_msg_elem = add_p('スタッフIDとパスワードのどちらか、もしくは双方とも不正');
            }

            $stmt->close();
//             $mysqli->close();
            echo  $id . '-' . $current . '-' . $temporary . '-' . $privilege;
        } else {
//             $mysqli->close();
            $err_msg_elem = READ_FAILED . '2';
        }

    } catch (Exception $e) {
        $err_msg_elem = READ_FAILED . '3';
    }

    //TODO:下記finally内での処理を検討
    if(isset($mysqli)) {
        $mysqli->close();
    }

    if (isset($err_msg_elem)) {
        return $err_msg_elem . LF;
    }

    echo '(' . $id . ')' . '(' . $current . ')' . '(' . $temporary . ')' . '(' . $privilege . ')';

    if (!isset($current) && isset($temporary) && strcmp($prm_post[STAFF_PASS], $temporary) === I_0) {
        header('Location: ./staff_first_login.php?staff_id=' . $staff_id);
    } else if(!password_verify($prm_post[STAFF_PASS], $current)) {
        return add_p('スタッフIDとパスワードのどちらか、もしくは双方とも不正');
    } else {
        session_start();
        $_SESSION[LOGIN] = LOGIN;
        $_SESSION[STAFF_ID] = $id;
        header('Location: ../system/system_top.php');
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

}
?>
