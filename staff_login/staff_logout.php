<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'const.php';

$_SESSION = array();
if (isset($_COOKIE[session_name()]) == true) {
    setcookie(session_name(), EMPTY_STR, time() - 42000, '/');
}
@session_destroy();
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
<p>ログアウトした</p>
<a href="./staff_login.html">スタッフログインへ</a>
</body>
</html>