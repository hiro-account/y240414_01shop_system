<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'sortedFunc.php');

function get_content_arr($prm_post) {
    $staff_id = NULL;

    for ($i = intval($prm_post['first_staff_id']); $i <= intval($prm_post['last_staff_id']); $i++) {
        if (isset($prm_post['staff_id_' . $i])) {
            $staff_id = $i;

            break;
        }
    }

    $column_arr = array('スタッフID' => 'id',
        '氏' => 'last_name',
        '名' => 'first_name',
        '氏（カナ）' => 'last_name_kana',
        '名（カナ）' => 'first_name_kana',
        '性別' => 'sex',
        '生年月日' => 'birthday',
        '登録日時' => 'created_date',
        '更新日時' => 'updated_date');

    $mixed = NULL;

    try {
        $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT ' . implode(', ', $column_arr) . ' FROM mst_staff WHERE id=?', array($staff_id));
        $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {

    }

    //TODO:選択肢と共通化
    $sex_arr = array('1' => '男', '2' => '女', '3' => '未選択');

    $rtn = NULL;

    foreach ($column_arr as $key => $value) {
        $processedValue = NULL;

        switch ($value) {
            case 'sex':
                $processedValue = $sex_arr[$mixed[$value]];
                break;

            case 'birthday':
//                 $mV = $mixed[$value];
//                 $processedValue = substr($mV, 0, 4) . '年' . substr($mV, 4, 2) . '月' . substr($mV, 6, 2) . '日';
//                 $split_arr = split_str_rtn_arr($mixed[$value], array(4, 2));
                $split_arr = explode('-', $mixed[$value]);
                $processedValue = $split_arr[0] . '年' . process_month_and_day($split_arr[1]) . '月' . process_month_and_day($split_arr[2]) . '日';
                break;

            case 'created_date':
            case 'updated_date':
                $processedValue = $mixed[$value];
                break;

            default:
                $processedValue = $mixed[$value];
                break;
        }


        $rtn .= '<tr><td>' . $key . '</td><td>：' . $processedValue . '</td></tr>' . LF;
    }

    return $rtn;
}



?>
