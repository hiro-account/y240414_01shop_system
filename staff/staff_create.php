<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once('./staff_create_ctrl.php');
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
<?= get_content(); ?>

<input type="hidden" name="from" value="create">
<div class="m-t-1em"><input type="submit" value="確認"></div>
</form>
<ul class="lowlnk">
<li><a href="./staff_top.php">スタッフ管理へ</a></li>
</ul>
</body>
</html>