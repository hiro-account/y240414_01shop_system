<?php
require_once('./staff_create_done_ctrl.php');
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
<h2>スタッフ登録</h2>
<?= get_content($_POST); ?>
<div><a href="./staff_create.php">スタッフ登録へ</a></div>
<div class="m-t-1em"><a href="./staff_top.php">スタッフ管理へ</a></div>
</body>
</html>