<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'func.php');



const CHECKED = ' checked';

function get_tbl_elem($prm_staff_data_arr) {
    $id_content = NULL;
    $staff_data_arr = NULL;

    if (isset($prm_staff_data_arr)) {
        $id_content =<<<EOI

<tr>
<td>スタッフID</td><td class="p-l-5">{$prm_staff_data_arr['id']}</td>
</tr>
EOI;
        $staff_data_arr = $prm_staff_data_arr;
    } else {
        $id_content = '';
        $staff_data_arr = array(
            'last_name' => ""
            , 'first_name' => ""
            , 'last_name_kana' => ""
            , 'first_name_kana' => ""
            , 'sex' => 0
            , 'birth_year' => NULL
           , 'birth_month' => NULL
           , 'birth_day' => NULL
           , 'privilege' => NULL
        );
    }

    $opt_sex = build_opt_sex($staff_data_arr['sex']);
    $opt_year = build_opt_year($staff_data_arr['birth_year']);
    $opt_month = build_opt_month_day(12, $staff_data_arr['birth_month']);
    $opt_day = build_opt_month_day(31, $staff_data_arr['birth_day']);

    $privilege_checked_o = NULL;
    $privilege_checked_a = NULL;

    switch ($staff_data_arr['privilege']) {
        case 'O':
            $privilege_checked_o = CHECKED;
            $privilege_checked_a = '';
            break;

        case 'A':
            $privilege_checked_o = '';
            $privilege_checked_a = CHECKED;
            break;

        default:
            $privilege_checked_o = '';
            $privilege_checked_a = '';
            break;
    }

    return <<<EOC
<table>{$id_content}
<tr>
<td>氏</td><td><input type="text" name="txt_last_name" value="{$staff_data_arr['last_name']}" class="w-100"></td>
</tr>
<tr>
<td>名</td><td><input type="text" name="txt_first_name" value="{$staff_data_arr['first_name']}" class="w-100"></td>
</tr>
<tr>
<td>氏（カナ）</td><td><input type="text" name="txt_last_name_kana" value="{$staff_data_arr['last_name_kana']}" class="w-100"></td>
</tr>
<tr>
<td>名（カナ）</td><td><input type="text" name="txt_first_name_kana" value="{$staff_data_arr['first_name_kana']}" class="w-100"></td>
</tr>
<tr>
<td>性別</td><td><select name="slct_sex">
{$opt_sex}</select></td>
</tr>
<tr>
<td>生年月日</td><td><select name="slct_birth_year">
{$opt_year}</select>年
<select name="slct_birth_month">
{$opt_month}</select>月
<select name="slct_birth_day">
{$opt_day}</select>日</td>
</tr>
<tr>
<td>権限</td><td><span><input type="radio" name="rdo_privilege" id="ordinary" value="O"{$privilege_checked_o}><label for="ordinary">一般</label></span>
<span class="m-l-10"><input type="radio" name="rdo_privilege" id="administrator" value="A"{$privilege_checked_a}><label for="administrator">管理者</label></span></td>
</tr>
</table>
EOC;
}
?>
