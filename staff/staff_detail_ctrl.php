<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'CmnPdo.php';

const BIRTHDAY = 'birthday';

const UPDATED_DATE = 'updated_date';

const S_NICHIJI = '日時';

const S_YOMIDASHI_SHIPPAI = TS_P . S_STAFF . '詳細' . S_YOMIDASHI . S_SHIPPAI . S_SYSTEM_SHOGAI_HASSEI . TE_P . LF;

//TODO:下記三定数他ファイルとの共通化検討
const HTML_TYPE_HIDDEN = '<input type="' . HIDDEN . '" name="';
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
  , s.updated_date AS s_updated_date
  , pr.updated_date AS pr_updated_date
  , pa.temporary AS temporary
FROM
  m_staff_for_dev AS s INNER JOIN t_privilege_for_dev AS pr ON s.id = pr.id INNER JOIN t_password_for_dev AS pa ON s.id = pa.id
WHERE
  s.id = ?
EOQ;

function get_content($prm_post) {
    $staff_id = NULL;

    for ($i = intval($prm_post['first_staff_id']); $i <= intval($prm_post['last_staff_id']); $i++) {
        if (isset($prm_post['staff_id_' . $i])) {
            $staff_id = $i;

            break;
        }
    }

    $mixed_arr = array();

    try {
        $cmn_pdo = new CmnPdo();
        $stmt = $cmn_pdo->prepare(QUERY);
        $exe_result = $stmt->execute(array($staff_id));

        while (TRUE) {
            $mixed = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($mixed == FALSE) {
                break;
            }

            $mixed_arr[] = $mixed;
        }
    } catch (Exception $e) {
        return S_YOMIDASHI_SHIPPAI;
    }

    if (!$exe_result) {
        //
        return S_YOMIDASHI_SHIPPAI;
    }

    //TODO:下記不要か
    if (count($mixed_arr) !== I_1) {
        // 戻り値が一件以外の場合
        return S_YOMIDASHI_SHIPPAI;
    }

    $mixed_0 = $mixed_arr[I_0];

    $column_arr = array(
        ID => S_STAFF . U_ID
        , LAST_NAME => S_SHI
        , FIRST_NAME => S_MEI
        , LAST_NAME. KANA => S_SHI . S_KANA
        , FIRST_NAME . KANA => S_MEI . S_KANA
        , SEX => S_SEIBETSU
        , BIRTHDAY => S_SEINENGAPPI
        , PRIVILEGE => S_KENGEN
        , 'created_date' => S_TOROKU . S_NICHIJI
        , UPDATED_DATE => S_KOSHIN . S_NICHIJI
        , TEMPORARY => '備考'
    );

    $row = NULL;
    $hidden = NULL;

    foreach ($column_arr as $key => $value) {
//         $select_result_value = $mixed_0[$key];
        $processed_value = NULL;
        $hidden_name = NULL;
        $hidden_value = NULL;

        $b_y = NULL;
        $b_m = NULL;
        $b_d = null;

        switch ($key) {
            case SEX:
                $processed_value = S_SEIBETSU_ARR[$mixed_0[$key]];

                $hidden_name = $key;
                $hidden_value = $mixed_0[$key];
                break;

            case BIRTHDAY:
                $b_y = $mixed_0[BIRTH_YEAR];
                $b_m = $mixed_0[BIRTH_MONTH];
                $b_d = $mixed_0[BIRTH_DAY];

                $processed_value = $b_y . S_NEN . process_month_and_day($b_m) . S_TSUKI . process_month_and_day($b_d) . S_HI . '（'
                    . get_age($b_y . sprintf('%02d', $b_m) . sprintf('%02d', $b_d)) . '歳）';//TODO:sprintf削除検討
                break;

            case PRIVILEGE:
                $processed_value = S_KENGEN_ARR[$mixed_0[$key]];

                $hidden_name = $key;
                $hidden_value = $mixed_0[$key];
                break;

            case UPDATED_DATE:
                //TODO:「's_'」、「'pr_'」ともここにしか存在しないため、とりあえずこのままで可(09/28)
                $s_updt_value = $mixed_0['s_' . $key];
                $pr_updt_value = $mixed_0['pr_' . $key];

                $s_date_time = new DateTime($s_updt_value);
                $pr_date_time = new DateTime($pr_updt_value);

                $processed_value = $s_date_time < $pr_date_time ? $pr_updt_value : $s_updt_value;

                $hidden_name = $key;
                $hidden_value = $processed_value;
                break;

            case TEMPORARY:
                if (!isset($mixed_0[$key])) {
                    continue 2;
                }

                //TODO:「'仮'」、「前」ともここにしか存在しないため、とりあえずこのままで可(09/28)
                $processed_value = '仮'. S_PASSWORD . S_HENKO . '前';
                break;

            default:
                $processed_value = $mixed_0[$key];

                $hidden_name = $key;
                $hidden_value = $mixed_0[$key];
                break;
        }

        $row .= '<tr><td>' . $value . '</td><td>：' . $processed_value . '</td></tr>' . LF;

        if (isset($hidden_name) && isset($hidden_value)) {
            $hidden .= HTML_TYPE_HIDDEN . $hidden_name . HTML_VALUE . $hidden_value . HTML_CLOSE . LF;
        }
    }

    foreach (array(BIRTH_YEAR, BIRTH_MONTH, BIRTH_DAY) as $value) {
        $hidden .= HTML_TYPE_HIDDEN . $value . HTML_VALUE . $mixed_0[$value] . HTML_CLOSE . LF;
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
