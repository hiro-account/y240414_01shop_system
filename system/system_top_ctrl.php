<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';

function get_content($prm_staff_privilege) {
    $content = <<<EOC
<ul>
<li><a href="../commodity/commodity_top.php">商品管理(Under construction)</a></li>

EOC;

    if (strcmp($prm_staff_privilege, PRIVILEGE_A) === I_0) {
        $content .= '<li><a href="../staff/staff_top.php">スタッフ管理</a></li>' . LF;
    }

    return $content . '</ul>' . LF;
}
?>
