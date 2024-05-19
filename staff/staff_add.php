<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
st_session();
check_login($_SESSION);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../cmn_css/style.css">
<title></title>
</head>
<body>
<div><?= get_staff_id_and_logout(); ?></div>
<h4>ショップシステム</h4>
<h2>スタッフ登録</h2>
<form method="post" action="staff_create_confirm.php">
<table>
<!--<tr>
<td>スタッフ名</td><td><input type="text" name="staff_name" class="w-100"></td>
</tr>-->
<tr>
<td>氏</td><td><input type="text" name="txt_last_name" class="w-100"></td>
</tr>
<tr>
<td>名</td><td><input type="text" name="txt_first_name" class="w-100"></td>
</tr>
<tr>
<td>氏（カナ）</td><td><input type="text" name="txt_last_name_kana" class="w-100"></td>
</tr>
<tr>
<td>名（カナ）</td><td><input type="text" name="txt_first_name_kana" class="w-100"></td>
</tr>
<tr>
<td>性別</td><td><select name="slct_sex">
<?= build_opt_sex() ?>
</select></td>
</tr>
<tr>
<td>生年月日</td><td><select name="slct_birth_year">
<?= build_opt_year() ?>
</select>年
<select name="slct_birth_month">
<?= build_opt_month_day(1, 12) ?>
</select>月
<select name="slct_birth_day">
<?= build_opt_month_day(1, 31) ?>
</select>日</td>
</tr>
<tr>
<td>パスワード</td><td><input type="password" name="txt_password_1" class="w-100"></td>
</tr>
<tr>
<td>パスワード（確認）</td><td><input type="password" name="txt_password_2" class="w-100"></td>
</tr>
</table>
<p><input type="submit" value="確認"></p>
<p><a href="./staff_top.php">戻る</a></p>
</form>
</body>
</html>