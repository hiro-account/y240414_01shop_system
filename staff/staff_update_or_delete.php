<?php
require_once 'staff_update_or_delete_ctrl.php';
session_cache_limiter('private_no_expire');
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
<?= get_content($_POST); ?>
</body>
</html>