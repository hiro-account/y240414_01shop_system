<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'const.php');
require_once './staff_func.php';
require_once  $to_cmn . 'CmnMySqlI.php';

const BIRTH_YEAR = I_0;
const BIRTH_MONTH = I_1;
const BIRTH_DATE = I_2;



function get_content($prm_post) {
    if (isset($prm_post['staff_update'])) {
        return updt_staff($prm_post);
    } else if (isset($prm_post['staff_delete'])) {
        return del_staff($prm_post['staff_id']);
    }




}

function updt_staff($prm_post) {
//     return get_tbl_elem(NULL);


    try {
//         throw new Exception();
        $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT id, last_name, first_name, last_name_kana, first_name_kana, sex, birthday FROM m_staff WHERE id=?', array($prm_post['staff_id']));
        $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {

    }

    $birthday = explode('-', $mixed['birthday']);
    $staff_data_arr = array('last_name' => $mixed['last_name'], 'first_name' => $mixed['first_name'], 'last_name_kana' => $mixed['last_name_kana'], 'first_name_kana' => $mixed['first_name_kana']
        , 'sex' => $mixed['sex'], 'birth_year' => $birthday[BIRTH_YEAR], 'birth_month' => $birthday[BIRTH_MONTH], 'birth_date' => $birthday[BIRTH_DATE]);

    return get_content_for_updt(get_tbl_elem($staff_data_arr));
}

function get_content_for_updt($prm_message) {
    $a_history_back = A_HISTORY_BACK;
    return <<<EOC
<h2>スタッフ更新</h2>
<form method="post" action="./staff_create_confirm.php">
{$prm_message}
<div class="m-t-1em"><input type="submit" value="確認"></div>
</form>
<ul class="lowlnk">
<li><a href="./staff_top.php">スタッフ管理へ</a></li>
</ul>

EOC;
}





//TODO:都度値を返すか変数で値を保持しておいて最後に一度だけ返すか検討
function del_staff($prm_staff_id) {
    mysqli_report(MYSQLI_REPORT_STRICT);

    $msg = NULL;

    try {
//         throw new Exception();
//         execute_sql_rtn_PDOStatement('UPDATE m_staff SET delete_flag=1 WHERE id=?', array($prm_staff_id));
        $mysqli = new CmnMySqlI();
        $mixed = $mysqli->query('UPDATE t_logical_delete_for_dev SET flag=1 WHERE id=' . $prm_staff_id);

        if (!$mixed['rows'] === I_1) {
            throw new Exception();
        }

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
