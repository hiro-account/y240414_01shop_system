<?php
require_once('./err_ctrl.php');
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
<h4>ショップシステム</h4>
<h2><?= $content_arr[H2] ?></h2>
<?= $content_arr['err_tmp'] ?>
<div><?= $content_arr[A] ?></div>
</body>
</html>