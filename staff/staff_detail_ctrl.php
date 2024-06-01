<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'sortedFunc.php');

function get_content($prm_post) {
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
        $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT ' . implode(', ', $column_arr) . ' FROM mst_staff_for_dev WHERE id=?', array($staff_id));
        $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);
// throw new Exception();
    } catch (Exception $e) {
        return add_p('スタッフ詳細読み出し失敗（システム障害発生）') . LF;
    }

    //TODO:選択肢と共通化
    $sex_arr = array('1' => '男', '2' => '女', '3' => '未選択');

    $row = NULL;

    foreach ($column_arr as $key => $value) {
        $processedValue = NULL;

        switch ($value) {
            case 'sex':
                $processedValue = $sex_arr[$mixed[$value]];
                break;

            case 'birthday':
                $split_arr = explode('-', $mixed[$value]);
                $processedValue = $split_arr[0] . '年'
                    . process_month_and_day($split_arr[1]) . '月'
                    . process_month_and_day($split_arr[2]) . '日（'
                    . get_age(str_replace('-', '', $mixed[$value])) . '歳）';
                break;

            case 'created_date':
            case 'updated_date':
                $processedValue = $mixed[$value];
                break;

            default:
                $processedValue = $mixed[$value];
                break;
        }


        $row .= '<tr><td>' . $key . '</td><td>：' . $processedValue . '</td></tr>' . LF;
    }

    return <<<EOC
<form method="post" action="staff_update_or_delete.php">
<table>
{$row}</table>
<div class="m-t-1em sbmt"><input type="submit" name="staff_update" value="更新"><input type="submit" name="staff_delete" value="削除"></div>
<input type="hidden" name="staff_id" value="{$staff_id}">
</form>

EOC;
}



?>
