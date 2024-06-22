<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once $to_cmn . 'MyDB.php';

//TODO:定数移動
const READ_FAILED = '<p>スタッフ一覧読み出し失敗（システム障害発生）</p>';
const A_STAFF_CREATE = '<div><a href="staff_create.php">スタッフ登録</a></div>' . LF;
const INPUT_HIDDEN = '<input type="hidden" name="';
const VALUE = '" value="';

function get_content() {
    // mysqliのコンストラクタの例外用設定
    mysqli_report(MYSQLI_REPORT_STRICT);

    $mysqli = NULL;
    $result = NULL;

    $staff_list = NULL;
    $first_staff_id = NULL;
    $last_staff_id = NULL;

    try {
//         $mysqli = new mysqli('localhost', 'root', '', 'y240608_01');

//         if ($mysqli->connect_error) {
//             return READ_FAILED;
//         } else {
//             $mysqli->set_charset('utf8');
//         }

        $query =<<< EOQ
        SELECT m.id AS id , CONCAT(m.last_name, m.first_name) AS name
        FROM m_staff_for_dev AS m INNER JOIN t_logical_delete_for_dev AS d ON m.id = d.id
        WHERE d.flag = FALSE
        ORDER BY m.id
        EOQ;

//         $result = $mysqli->query($query);

//         while (TRUE) {
//             $rslt_arr = $result->fetch_assoc();

//             if (!$rslt_arr) {
//                 break;
//             }

//             $last_staff_id = $rslt_arr['id'];
//             $staff_list .= '<tr><td>' . $last_staff_id . '</td><td>' . $rslt_arr['last_name'] . $rslt_arr['first_name'] . '</td>'
//                 . '<td class="t-a-c"><input type="submit" name="staff_id_' . $last_staff_id . '" value="表示"></td></tr>' . LF;

//                 if (!isset($first_staff_id)) {
//                     $first_staff_id = $last_staff_id;
//                 }
//         }
        $db = new MyDB();
        $tmp = $db->query($query);

        foreach ($tmp['result'] as $value) {
            $last_staff_id = $value['id'];
//             $staff_list .= '<tr><td>' . $last_staff_id . '</td><td>' . $value['last_name'] . $value['first_name'] . '</td>' . '<td class="t-a-c"><input type="submit" name="staff_id_' . $last_staff_id . '" value="表示"></td></tr>' . LF;
            $staff_list .= '<tr><td>' . $last_staff_id . '</td><td>' . $value['name'] . '</td><td class="t-a-c"><input type="submit" name="staff_id_' . $last_staff_id . '" value="表示"></td></tr>' . LF;

            if (! isset($first_staff_id)) {
                $first_staff_id = $last_staff_id;
            }
        }




    } catch (Exception $e) {
        return READ_FAILED . LF;
    } finally {
        //TODO:クローズ処理は下記で可か確認
        if (isset($result)) {
            $result->close();
        }

        if (isset($mysqli)) {
            $mysqli->close();
        }
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
