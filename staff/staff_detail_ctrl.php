<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'sortedFunc.php');
require_once $to_cmn . 'CmnMySqlI.php';
require_once $to_cmn . 'query.php';

const READ_FAILED = '<p>スタッフ詳細読み出し失敗（システム障害発生）</p>' . LF;

function get_content($prm_post) {
    $staff_id = NULL;

    for ($i = intval($prm_post['first_staff_id']); $i <= intval($prm_post['last_staff_id']); $i++) {
        if (isset($prm_post['staff_id_' . $i])) {
            $staff_id = $i;

            break;
        }
    }

    $column_arr = array('スタッフID' => 'id'
        , '氏' => 'last_name'
        , '名' => 'first_name'
        , '氏（カナ）' => 'last_name_kana'
        , '名（カナ）' => 'first_name_kana'
        , '性別' => 'sex'
        , '生年月日' => 'birthday'
        , '権限' => 'privilege'
        , '登録日時' => 'created_date'
        , '更新日時' => 'updated_date');

    $query =<<<EOQ
SELECT
  s.id AS id
  , s.last_name AS last_name
  , s.first_name AS first_name
  , s.last_name_kana AS last_name_kana
  , s.first_name_kana AS first_name_kana
  , s.sex AS sex
  , s.birth_year AS birth_year
  , s.birth_month AS birth_month
  , s.birth_day AS birth_day
  , pr.privilege AS privilege
  , s.creator_id AS creator_id
  , s.created_date AS created_date
  , s.updater_id AS updater_id
  , s.updated_date AS updated_date
FROM
  m_staff_for_dev AS s INNER JOIN t_privilege_for_dev as pr on s.id=pr.id
WHERE
  s.id={$staff_id}
EOQ;

    $result_array = execute_query($query);

    if (isset($result_array[EXCEPTION])) {
        return READ_FAILED;
    }

    $select_result = $result_array[STMT]->fetch();

    //TODO:選択肢と共通化
    $sex_arr = array('0' => '-', '1' => '男', '2' => '女', '3' => '未選択');
    $privilege_arr = array('O' => '一般', 'A' => '管理者');

    $row = NULL;

    foreach ($column_arr as $key => $value) {
        $processedValue = NULL;

        switch ($value) {
            case 'sex':
                $processedValue = $sex_arr[$select_result[$value]];
                break;

            case 'birthday':
                $processedValue = $select_result['birth_year'] . '年'
                    . process_month_and_day($select_result['birth_month']) . '月'
                    . process_month_and_day($select_result['birth_day']) . '日（'
                        . get_age($select_result['birth_year'] . $select_result['birth_month'] .$select_result['birth_day']) . '歳）';
                break;

            case 'privilege':
                $processedValue = $privilege_arr[$select_result[$value]];
                break;

            default:
                $processedValue = $select_result[$value];
                break;
        }

        $row .= '<tr><td>' . $key . '</td><td>：' . $processedValue . '</td></tr>' . LF;
    }

    return <<<EOC
<form method="post" action="staff_update_or_delete.php" onSubmit="return confirmDelete()">
<table>
{$row}</table>
<div class="m-t-1em sbmt"><input type="submit" name="staff_update" value="更新" onClick="sbmt_nm='staff_update'"><input type="submit" name="staff_delete" value="削除" onClick="sbmt_nm='staff_delete'"></div>
<input type="hidden" name="staff_id" value="{$select_result['id']}">
</form>

EOC;
}
?>
