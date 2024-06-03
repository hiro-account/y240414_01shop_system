<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'func.php');



function get_content($prm_post) {
    if (isset($prm_post['staff_update'])) {
        return updt_staff($prm_post);
    } else if (isset($prm_post['staff_delete'])) {
        return del_staff($prm_post['staff_id']);
    }




}

function updt_staff($prm_post) {

}




//TODO:都度値を返すか変数で値を保持しておいて最後に一度だけ返すか検討
function del_staff($prm_staff_id) {
    $msg = NULL;

    try {
//         throw new Exception();
        execute_sql_rtn_PDOStatement('UPDATE mst_staff_for_dev SET delete_flag=1 WHERE id=?', array($prm_staff_id));
        $msg = '削除完了';
    } catch (Exception $e) {
        $msg = '削除失敗（システム障害発生）';
    }

    return get_content_for_del($msg);
}

function get_content_for_del($prm_message) {
    return <<<EOC
<h2>スタッフ削除</h2>
<p>{$prm_message}</p>
<ul class="lowlnk">
<!--<li><a href="./staff_create.php">スタッフ登録へ</a></li>-->
<li><a href="./staff_top.php">スタッフ管理へ</a></li>
</ul>

EOC;
}





?>
