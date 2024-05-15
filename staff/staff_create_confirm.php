<?php
require_once('./staff_create_confirm_ctrl.php');
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
<p><a href="javascript: history.back();">戻る</a></p>
</body>
</html>