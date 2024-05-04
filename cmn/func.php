<?php
require_once 'const.php';

function st_session() {
    session_start();
    session_regenerate_id(true);
}

function check_login($sess_arr) {
    if (!isset($sess_arr[LOGIN])) {
        header(LOCATION . get_host_and_dir() . '/../cmn_err/err.php?from=' . SYSTEM_TOP . ERR_MSG . 'ログインしていない');
    }
}

function get_staff_id_and_logout() {
    return '<span>ログイン中のスタッフID：' . $_SESSION[STAFF_ID] . '</span><a href="' . get_host_and_dir() .'/../staff_login/staff_logout.php" style="margin-left: 1em;">ログアウト</a>';
}




//--------------------------------------------------------------------------------

function convert_sp_chara_rtn_arr($target_arr) {
    $converted_arr = array();

    foreach ($target_arr as $key => $val) {
        $converted_arr[$key] = htmlspecialchars($val);
    }

    return $converted_arr;
}


/**
 * 未入力の項目をチェックする
 *
 */
function check_unfilled_item_rtn_arr($item_txt_arr, $target_arr) {
    $val_arr = array();

    foreach ($item_txt_arr as $key => $val) {
        if (strlen(trim($target_arr[$key])) === 0) {
            $val_arr[$key] = $val . NOT_ENTERED;
        }
    }

    return $val_arr;
}

/**
 * 未選択の項目をチェックする
 *
 */
function check_unselected_item_rtn_arr($item_slct_arr, $target_arr) {
    $val_arr = array();

    foreach ($item_slct_arr as $key => $val) {
        if (intval($target_arr[$key]) === 0) {
            $val_arr[$key] = $val . 'が未選択';
        }
    }

    return $val_arr;
}

/**
 * メッセージをソートする
 *
 */
function sort_msg_rtn_arr($item_key_arr, $target_arr) {
    $val_arr = array();

    foreach ($item_key_arr as $val) {
        if (array_key_exists($val, $target_arr)) {
            $val_arr[] = $target_arr[$val];
        }
    }

    return $val_arr;
}




function check_alphanumeric_rtn_arr($item_key_arr, $target_arr) {
    $val_arr = array();

    foreach ($item_key_arr as $key => $val) {
        if (!preg_match("/^[a-zA-Z0-9]+$/", $target_arr[$key])) {
            $val_arr[] = $val . 'に英数字以外が入力された';//TODO:定数化検討
        }
    }

    return $val_arr;

}



//----------------------------------------

function build_opt_sex() {
    $opt_arr = array('-', '男', '女', '未選択');

    $opt_gender = build_opt_elem_with_selected(I_0, $opt_arr[I_0]);

    for ($i = I_1; $i < count($opt_arr); $i++) {
        $opt_gender .= build_opt_elem($i, $opt_arr[$i]);
    }

    return $opt_gender;
}

function build_opt_year() {
    $opt_year = build_opt_elem_with_selected(I_0, '-');
    $int_y = intval(date('Y'));

    for ($i = $int_y - 16; $i >= $int_y - 90; $i--) {
        $str_i = strval($i);
        $opt_year .= build_opt_elem($str_i, $str_i);
    }

    return $opt_year;
}

function build_opt_month_day($from, $to) {
    $opt_month_day = build_opt_elem_with_selected(I_0, '-');

    for ($i = $from; $i <= $to; $i++) {
        $opt_month_day .= build_opt_elem($i, $i);
    }

    return $opt_month_day;
}


//----------------------------------------

function add_p($target_param) {
    return '<p>' . $target_param . '</p>';
}

//TODO:関数内で例外を補足せず、関数の使用側で補足する方向で
function execute_sql_rtn_PDOStatement($statement, $input_parameter_arr) {
    $dbh = new PDO('mysql:dbname=from240414_01;host=localhost', 'root', '');
    $dbh->query('SET NAMES utf8');
    $pdo_stmt = $dbh->prepare($statement);

    if ($input_parameter_arr == null) {
        $pdo_stmt->execute();
    } else {
        $pdo_stmt->execute($input_parameter_arr);
    }

    $dbh = null;

    return $pdo_stmt;
}

/*--------------------------------------------------------------------------------
以下private関数
--------------------------------------------------------------------------------*/
function get_host_and_dir() {
    return HTTP . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
}


function build_opt_elem($prm_val, $prm_cntnt) {
    return '<option value="' . $prm_val . '">' . $prm_cntnt . '</option>' . LF;
}

function build_opt_elem_with_selected($prm_val, $prm_cntnt) {
    return '<option value="' . $prm_val . '" selected="selected">' . $prm_cntnt . '</option>' . LF;
}



?>
