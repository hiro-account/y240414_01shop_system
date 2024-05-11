<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

//TODO:関数名変更検討
function get_staff_list() {
    $staff_list = NULL;
//     $hidden = NULL;
    $id_str = NULL;

    try {
        $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT id, last_name, first_name FROM mst_staff WHERE delete_flag=FALSE ORDER BY id', NULL);

        while (TRUE) {
            $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);

            if ($mixed == FALSE) {
                break;
            }

//             $staff_list .= '<tr><td><a href="./staff_detail.php?staff_id=' . $mixed['id'] . '">' . $mixed['id'] . '</a></td><td>' . $mixed['name'] . '</td></tr>' . LF;
            $staff_list .= '<tr><td>' . $mixed['id'] . '</td><td>' . $mixed['last_name'] . $mixed['first_name'] . '</td>'
                . '<td class="t-a-c"><input type="submit" name="id_' . $mixed['id'] . '" value="表示"></td></tr>' . LF;
            //             $hidden .= ''
                $id_str .= $mixed['id'] . DELIMITER;
        }






    } catch (Exception $e) {

    }






    return $staff_list . '<input type="hidden" name="id_str" value="' . $id_str . '">' . LF;
}


?>
