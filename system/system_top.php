<?php
require_once 'system_top_ctrl.php';
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
<?= get_content($_SESSION[PRIVILEGE]) ?>
</body>
</html>