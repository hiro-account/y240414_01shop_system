<?php
//TODO:'staff_id'→STAFF_IDへの置換を検討
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../cmn_css/style.css">
<title></title>
</head>
<body>
<h4>ショップシステム</h4>
<h2>初回ログインパスワード変更</h2>
<form method="post" action="./staff_first_login_confirm.php">
<table>
<tr>
<td>新しいパスワード</td><td><input type="password" name="txt_new_password_1" class="w-100"></td>
</tr>
<tr>
<td>新しいパスワード（確認）</td><td><input type="password" name="txt_new_password_2" class="w-100"></td>
</tr>
</table>
<input type="hidden" name="staff_id" value="<?= $_GET['staff_id'] ?>">
<input type="submit" value="変更" class="m-t-1em">
</form>
</body>
</html>