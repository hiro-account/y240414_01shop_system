<?php
require_once 'staff_login_confirm_ctrl.php';
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
<h2>スタッフログイン</h2>
<?= get_content($_POST); ?>
<div><a href="javascript: history.back();">戻る</a></div>
</body>
</html>