<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'func.php');





function get_tbl_elem($prm_staff_data_arr) {

    $staff_data_arr = NULL;

    if (isset($prm_staff_data_arr)) {
        $staff_data_arr = $prm_staff_data_arr;
    } else {
        $staff_data_arr = array('last_name' => "", 'first_name' => "", 'last_name_kana' => "", 'first_name_kana' => ""
            , 'sex' => 0, 'birth_year' => NULL, 'birth_month' => NULL, 'birth_date' => NULL);
    }

    $opt_sex = build_opt_sex($staff_data_arr['sex']);
    $opt_year = build_opt_year($staff_data_arr['birth_year']);
    $opt_month = build_opt_month_day(12, $staff_data_arr['birth_month']);
    $opt_day = build_opt_month_day(31, $staff_data_arr['birth_date']);

    return <<<EOC
<table>
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
{$opt_sex}
</select></td>
</tr>
<tr>
<td>生年月日</td><td><select name="slct_birth_year">
{$opt_year}
</select>年
<select name="slct_birth_month">
{$opt_month}
</select>月
<select name="slct_birth_day">
{$opt_day}
</select>日</td>
</tr>
<tr>
<td>パスワード</td><td><input type="password" name="txt_password_1" class="w-100"></td>
</tr>
<tr>
<td>パスワード（確認）</td><td><input type="password" name="txt_password_2" class="w-100"></td>
</tr>
</table>

EOC;


}





?>
