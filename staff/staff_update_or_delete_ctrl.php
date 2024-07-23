<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'const.php');
require_once './staff_func.php';
require_once  $to_cmn . 'query.php';

const PREV = 'prev_';


function get_content($prm_post) {
    if (isset($prm_post['staff_update'])) {
        return updt_staff($prm_post);
    } else if (isset($prm_post['staff_delete'])) {
        return del_staff($prm_post['staff_id']);
    }
}

function updt_staff($prm_post) {
    $staff_data_arr = build_key_value_arr_and_hidden($prm_post);

    return get_content_for_updt(get_tbl_elem($staff_data_arr['key_value_arr']), $staff_data_arr['hidden']);
}

function build_key_value_arr_and_hidden($prm_value_arr) {
//     $key_arr = array(PREV . 'id', PREV . 'last_name', PREV . 'first_name', PREV . 'last_name_kana', PREV . 'first_name_kana', PREV . 'sex'
//         , PREV . 'birth_year', PREV . 'birth_month', PREV . 'birth_day', PREV . 'privilege');
    $key_arr = array('id', 'last_name', 'first_name', 'last_name_kana', 'first_name_kana', 'sex'
        , 'birth_year', 'birth_month', 'birth_day', 'privilege');

    $key_value_arr = array();
    $hidden = '<input type="hidden" name="id" value="' . $prm_value_arr['id'] . '">' . LF;

    foreach ($key_arr as $value) {
        $key_value_arr[$value] = $prm_value_arr[$value];
        $hidden .= '<input type="hidden" name="' . PREV . $value . '" value="' . $prm_value_arr[$value] . '">' . LF;
    }

    return array('key_value_arr' => $key_value_arr, 'hidden' => $hidden);
}

function get_content_for_updt($prm_message, $prm_joined_str) {
    $a_history_back = A_HISTORY_BACK;
    return <<<EOC
<h2>スタッフ更新</h2>
<form method="post" action="./staff_create_confirm.php">
{$prm_message}
{$prm_joined_str}<input type="hidden" name="from" value="update">
<div class="m-t-1em"><input type="submit" value="確認"></div>
</form>
<ul class="lowlnk">
<li><a href="./staff_top.php">スタッフ管理へ</a></li>
</ul>

EOC;
}

//TODO:都度値を返すか変数で値を保持しておいて最後に一度だけ返すか検討
function del_staff($prm_staff_id) {
    $msg = NULL;

    $result_array = execute_query('UPDATE t_logical_delete_for_dev SET flag=1 WHERE id=' . $prm_staff_id);

    if (isset($result_array[EXCEPTION])) {
        $msg = '削除失敗（システム障害発生）';
    } else {
        $msg = '削除完了';
    }

    return get_content_for_del($msg);
}

function get_content_for_del($prm_message) {
    return <<<EOC
<h2>スタッフ削除</h2>
<p>{$prm_message}</p>
<ul class="lowlnk">
<li><a href="./staff_top.php">スタッフ管理へ</a></li>
</ul>

EOC;
}
?>
