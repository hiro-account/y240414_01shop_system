<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
// require_once($to_cmn . 'func.php');
require_once('./staff_detail_ctrl.php');
st_session();
check_login($_SESSION);
// $content_arr = get_content_arr($_GET);
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
<h2>スタッフ詳細</h2>
<form method="post" action="staff_detail.php">
<table>
<?= get_content_arr($_POST); ?>
</table>
</form>
<p><a href="./staff_top.php">戻る</a></p>
</body>
</html>