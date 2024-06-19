<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once './system_top_ctrl.php';
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
<h2>メインメニュー</h2>
<!-- <ul> -->
<!-- <li><a href="">商品管理</a></li> -->
<!-- <li><a href="../staff/staff_top.php">スタッフ管理</a></li> -->
<!-- </ul> -->
<?= get_content($_SESSION['staff_privilege']) ?>
</body>
</html>