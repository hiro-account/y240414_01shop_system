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
<h2>商品管理</h2>
<p>Under construction</p>
<ul class="lowlnk">
<li><a href="../system/system_top.php">メインメニューへ</a></li>
</ul>
</body>
</html>