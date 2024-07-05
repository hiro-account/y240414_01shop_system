<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'const.php');
require_once './staff_func.php';
require_once  $to_cmn . 'query.php';

const QUERY =<<<EOQ
SELECT
  m.id AS id
  , m.last_name AS last_name
  , m.first_name AS first_name
  , m.last_name_kana AS last_name_kana
  , m.first_name_kana AS first_name_kana
  , m.sex AS sex
  , m.birth_year AS birth_year
  , m.birth_month AS birth_month
  , m.birth_day AS birth_day
  , pr.privilege AS privilege
FROM
  m_staff_for_dev AS m INNER JOIN t_privilege_for_dev AS pr ON m.id=pr.id
WHERE
  m.id=
EOQ;

function get_content($prm_post) {
    if (isset($prm_post['staff_update'])) {
        return updt_staff($prm_post);
    } else if (isset($prm_post['staff_delete'])) {
        return del_staff($prm_post['staff_id']);
    }
}

function updt_staff($prm_post) {
    $result_array = execute_query(QUERY . $prm_post['staff_id']);

    if (isset($result_array[EXCEPTION])) {
        return '例外発生';
    }

    $fetched_arr = $result_array[STMT]->fetch();

    return get_content_for_updt(get_tbl_elem($fetched_arr), implode(',', $fetched_arr));
}

function get_content_for_updt($prm_message, $prm_joined_str) {
    $a_history_back = A_HISTORY_BACK;
    return <<<EOC
<h2>スタッフ更新</h2>
<form method="post" action="./staff_create_confirm.php">
{$prm_message}
<div><input type="hidden" name="before" value="{$prm_joined_str}"></div>
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
