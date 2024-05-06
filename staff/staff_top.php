<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once('./staff_top_ctrl.php');
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
<h2>スタッフ管理</h2>
<div><a href="staff_add.php">新規登録</a></div>
<div class="m-t-05em">スタッフ一覧(更新、削除は詳細を表示)</div>
<form method="post" action="staff_detail.php">
<table class="border">
<tr><th>スタッフID</th><th>スタッフ名</th><th>詳細</th><tr>
<?= get_staff_list() ?>
</table>
</form>
</body>
</html>