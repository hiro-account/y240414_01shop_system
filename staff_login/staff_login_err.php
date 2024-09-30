<?php
require_once 'staff_login_err_ctrl.php';
$content_arr = get_content_arr_for_staff_login_err($_GET);
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
<?= $content_arr[H2_AND_ERR_MSG] ?>
<div><?= $content_arr[A] ?></div>
</body>
</html>
