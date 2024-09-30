<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';

const P_CHECKED = ' checked';

function get_tbl_elem($prm_staff_data_arr) {
    $id_content = NULL;
    $staff_data_arr = NULL;

    if (isset($prm_staff_data_arr)) {
        $id_content =<<<EOI

<tr>
<td>スタッフID</td><td class="p-l-5">{$prm_staff_data_arr[ID]}</td>
</tr>
EOI;
        $staff_data_arr = $prm_staff_data_arr;
    } else {
        $id_content = EMPTY_STR;
        $staff_data_arr = array(
            LAST_NAME => EMPTY_STR
            , FIRST_NAME => EMPTY_STR
            , LAST_NAME. KANA => EMPTY_STR
            , FIRST_NAME . KANA => EMPTY_STR
            , SEX => I_0
            , BIRTH_YEAR => NULL
           , BIRTH_MONTH => NULL
           , BIRTH_DAY => NULL
           , PRIVILEGE => NULL
        );
    }

    $opt_sex = build_opt_sex($staff_data_arr[SEX]);
    $opt_year = build_opt_year($staff_data_arr[BIRTH_YEAR]);
    $opt_month = build_opt_month_day(12, $staff_data_arr[BIRTH_MONTH]);
    $opt_day = build_opt_month_day(31, $staff_data_arr[BIRTH_DAY]);

    $privilege_checked_o = NULL;
    $privilege_checked_a = NULL;

    switch ($staff_data_arr[PRIVILEGE]) {
        case PRIVILEGE_O:
            $privilege_checked_o = P_CHECKED;
            $privilege_checked_a = EMPTY_STR;
            break;

        case PRIVILEGE_A:
            $privilege_checked_o = EMPTY_STR;
            $privilege_checked_a = P_CHECKED;
            break;

        default:
            $privilege_checked_o = EMPTY_STR;
            $privilege_checked_a = EMPTY_STR;
            break;
    }

    return <<<EOC
<table>{$id_content}
<tr>
<td>氏</td><td><input type="text" name="last_name" value="{$staff_data_arr[LAST_NAME]}" class="w-100"></td>
</tr>
<tr>
<td>名</td><td><input type="text" name="first_name" value="{$staff_data_arr[FIRST_NAME]}" class="w-100"></td>
</tr>
<tr>
<td>氏（カナ）</td><td><input type="text" name="last_name_kana" value="{$staff_data_arr[LAST_NAME. KANA]}" class="w-100"></td>
</tr>
<tr>
<td>名（カナ）</td><td><input type="text" name="first_name_kana" value="{$staff_data_arr[FIRST_NAME . KANA]}" class="w-100"></td>
</tr>
<tr>
<td>性別</td><td><select name="sex">
{$opt_sex}</select></td>
</tr>
<tr>
<td>生年月日</td><td><select name="birth_year">
{$opt_year}</select>年
<select name="birth_month">
{$opt_month}</select>月
<select name="birth_day">
{$opt_day}</select>日</td>
</tr>
<tr>
<td>権限</td><td><span><input type="radio" name="privilege" id="ordinary" value="O"{$privilege_checked_o}><label for="ordinary">一般</label></span>
<span class="m-l-10"><input type="radio" name="privilege" id="administrator" value="A"{$privilege_checked_a}><label for="administrator">管理者</label></span></td>
</tr>
</table>
EOC;
}
?>
