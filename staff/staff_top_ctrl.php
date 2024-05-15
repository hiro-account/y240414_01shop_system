<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

function get_content($prm_get) {
    $staff_list = NULL;
    $first_staff_id = NULL;
    $last_staff_id = NULL;

    try {
        $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT id, last_name, first_name FROM mst_staff WHERE delete_flag=FALSE ORDER BY id', NULL);

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

//                 $last_staff_id = $staff_id;
        }
    } catch (Exception $e) {

    }

    return array(
        'STAFF_LIST' => $staff_list,
        'FIRST_STAFF_ID' => $first_staff_id,
        'LAST_STAFF_ID' => $last_staff_id
    );




//     return $staff_list . '<input type="hidden" name="id_str" value="' . $id_str . '">' . LF;
}


?>
