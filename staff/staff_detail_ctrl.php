<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'sortedFunc.php');
require_once $to_cmn . 'CmnMySqlI.php';
require_once $to_cmn . 'query.php';

const READ_FAILED = '<p>スタッフ詳細読み出し失敗（システム障害発生）</p>' . LF;

const HTML_TYPE_HIDDEN = '<input type="hidden" name="';
const HTML_VALUE = '" value="';
const HTML_CLOSE = '">';

const QUERY =<<<EOQ
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
  , pa.temporary AS temporary
FROM
  m_staff_for_dev AS s INNER JOIN t_privilege_for_dev AS pr ON s.id=pr.id INNER JOIN t_password_for_dev AS pa ON s.id=pa.id
WHERE
  s.id=
EOQ;

function get_content($prm_post) {
    $staff_id = NULL;

    for ($i = intval($prm_post['first_staff_id']); $i <= intval($prm_post['last_staff_id']); $i++) {
        if (isset($prm_post['staff_id_' . $i])) {
            $staff_id = $i;

            break;
        }
    }

    $result_array = execute_query(QUERY . $staff_id);

    if (isset($result_array[EXCEPTION])) {
        return READ_FAILED;
    }

    $select_result = $result_array[STMT]->fetch();

    $column_arr = array(
        'id' => 'スタッフID'
        , 'last_name' => '氏'
        , 'first_name' => '名'
        , 'last_name_kana' => '氏（カナ）'
        , 'first_name_kana' => '名（カナ）'
        , 'sex' => '性別'
        , 'birthday' => '生年月日'
        , 'privilege' => '権限'
        , 'created_date' => '登録日時'
        , 'updated_date' => '更新日時'
        , 'temporary' => '備考'
    );

    //TODO:選択肢と共通化
    $sex_arr = array('0' => '-', '1' => '男', '2' => '女', '3' => '未選択');
    $privilege_arr = array('O' => '一般', 'A' => '管理者');

    $row = NULL;
$hidden = NULL;
    foreach ($column_arr as $key => $value) {
//         $select_result_value = $select_result[$key];
        $processedValue = NULL;
        $hidden_name = NULL;
        $hidden_value = NULL;

        $b_y = NULL;
        $b_m = NULL;
        $b_d = null;

        switch ($key) {
            case 'sex':
                $processedValue = $sex_arr[$select_result[$key]];

                $hidden_name = $key;
                $hidden_value = $select_result[$key];
                break;

            case 'birthday':
                $b_y = $select_result['birth_year'];
                $b_m = $select_result['birth_month'];
                $b_d = $select_result['birth_day'];

                $processedValue = $b_y . '年' . process_month_and_day($b_m) . '月' . process_month_and_day($b_d) . '日（'
                    . get_age($b_y . $b_m . $b_d) . '歳）';
                break;

            case 'privilege':
                $processedValue = $privilege_arr[$select_result[$key]];

                $hidden_name = $key;
                $hidden_value = $select_result[$key];
                break;

            case 'temporary':
                if (!isset($select_result[$key])) {
                    continue 2;
                }

                $processedValue = '仮パスワード変更前';
                break;

            default:
                $processedValue = $select_result[$key];

                $hidden_name = $key;
                $hidden_value = $select_result[$key];
                break;
        }

        $row .= '<tr><td>' . $value . '</td><td>：' . $processedValue . '</td></tr>' . LF;
        $hidden .= HTML_TYPE_HIDDEN . $hidden_name . HTML_VALUE . $hidden_value . HTML_CLOSE . LF;
    }

    foreach (array('birth_year', 'birth_month', 'birth_day') as $value) {
        $hidden .= HTML_TYPE_HIDDEN . $value . HTML_VALUE . $select_result[$value] . HTML_CLOSE . LF;
    }

    return <<<EOC
<form method="post" action="staff_update_or_delete.php" onSubmit="return confirmDelete()">
<table>
{$row}</table>
<div class="m-t-1em sbmt"><input type="submit" name="staff_update" value="更新" onClick="sbmt_nm='staff_update'"><input type="submit" name="staff_delete" value="削除" onClick="sbmt_nm='staff_delete'"></div>
{$hidden}</form>

EOC;
}
?>
