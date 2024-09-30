<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'CmnPdo.php';
require_once 'staff_func.php';

const K_V_ARR = 'key_value_arr';

const S_SAKUJO = '削除';

function get_content($prm_post) {
    if (isset($prm_post['staff_update'])) {
        return updt_staff($prm_post);
    } else if (isset($prm_post['staff_delete'])) {
        return del_staff($prm_post[ID]);
    }
}

function updt_staff($prm_post) {
    $staff_data_arr = build_key_value_arr_and_hidden($prm_post);

    return get_content_for_updt(get_tbl_elem($staff_data_arr[K_V_ARR]), $staff_data_arr[HIDDEN]);
}

function build_key_value_arr_and_hidden($prm_value_arr) {
    $key_arr = array(ID, LAST_NAME, FIRST_NAME, LAST_NAME. KANA, FIRST_NAME . KANA, SEX
        , BIRTH_YEAR, BIRTH_MONTH, BIRTH_DAY, PRIVILEGE);

    $key_value_arr = array();
    $hidden = '<input type="' . HIDDEN . '" name="id" value="' . $prm_value_arr[ID] . '">' . LF;

    foreach ($key_arr as $value) {
        $key_value_arr[$value] = $prm_value_arr[$value];
        $hidden .= '<input type="' . HIDDEN . '" name="' . PREV . $value . '" value="' . $prm_value_arr[$value] . '">' . LF;
    }

    return array(K_V_ARR => $key_value_arr, HIDDEN => $hidden);
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

    try {
        $cmn_pdo = new CmnPdo();
        $stmt = $cmn_pdo->prepare('UPDATE t_logical_delete_for_dev SET flag = 1 WHERE id = ?');
        $result = $stmt->execute(array($prm_staff_id));

        if (!$result) {
            $msg = S_SAKUJO . S_SHIPPAI . S_SYSTEM_SHOGAI_HASSEI;
        } else {
            $msg = S_SAKUJO . S_KANRYO;
        }
    } catch (Exception $e) {
        $msg = S_SAKUJO . S_SHIPPAI . S_SYSTEM_SHOGAI_HASSEI;
    } finally {
        return get_content_for_del($msg);
    }
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
