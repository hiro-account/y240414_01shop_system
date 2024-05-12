<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once('./staff_top_ctrl.php');
st_session();
check_login($_SESSION);
$content_arr = get_content_arr($_GET);
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
<div><a href="staff_add.php">スタッフ登録</a></div>
<div class="m-t-05em">スタッフ一覧(スタッフ更新、スタッフ削除はスタッフ詳細を表示)</div>
<form method="post" action="staff_detail.php">
<table class="border">
<tr><th>スタッフID</th><th>スタッフ名</th><th>スタッフ詳細</th><tr>
<?= $content_arr['STAFF_LIST'] ?>
</table>
<input type="hidden" name="first_staff_id" value="<?= $content_arr['FIRST_STAFF_ID'] ?>">
<input type="hidden" name="last_staff_id" value="<?= $content_arr['LAST_STAFF_ID'] ?>">
</form>
<p><a href="../system/system_top.php">戻る</a></p>
</body>
</html>