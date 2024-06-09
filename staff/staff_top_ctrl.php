<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

//TODO:定数移動
const A_STAFF_CREATE = '<div><a href="staff_create.php">スタッフ登録</a></div>' . LF;
const INPUT_HIDDEN = '<input type="hidden" name="';
const VALUE = '" value="';

function get_content() {
    $staff_list = NULL;
    $first_staff_id = NULL;
    $last_staff_id = NULL;

    try {
        $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT id, last_name, first_name FROM m_staff WHERE delete_flag=FALSE ORDER BY id', NULL);
// throw new Exception();
        while (TRUE) {
            $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mixed) {
                break;
            }

            $last_staff_id = $mixed['id'];
            $staff_list .= '<tr><td>' . $last_staff_id . '</td><td>' . $mixed['last_name'] . $mixed['first_name'] . '</td>'
                . '<td class="t-a-c"><input type="submit" name="staff_id_' . $last_staff_id . '" value="表示"></td></tr>' . LF;

                if (!isset($first_staff_id)) {
                    $first_staff_id = $last_staff_id;
                }
        }
    } catch (Exception $e) {
        return add_p('スタッフ一覧読み出し失敗（システム障害発生）') . LF;
    }

    if (!isset($staff_list)) {
        return A_STAFF_CREATE;
    }


    return A_STAFF_CREATE . FOR_STAFF_TOP . $staff_list . '</table>' . LF
        . INPUT_HIDDEN . 'first_staff_id' . VALUE . $first_staff_id . '">' . LF
        . INPUT_HIDDEN . 'last_staff_id' . VALUE . $last_staff_id . '">' . LF
        . '</form>' . LF;
}
?>
