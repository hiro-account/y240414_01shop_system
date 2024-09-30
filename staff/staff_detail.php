<?php
require_once 'staff_detail_ctrl.php';
st_session();
check_login($_SESSION);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../cmn_css/style.css">
<script src="../cmn_js/cmn.js"></script>
<title></title>
</head>
<body>
<div><?= get_staff_id_and_logout(); ?></div>
<h4>ショップシステム</h4>
<h2>スタッフ詳細</h2>
<?= get_content($_POST); ?>
<ul class="lowlnk">
<li><a href="./staff_top.php">スタッフ管理へ</a></li>
</ul>
</body>
</html>