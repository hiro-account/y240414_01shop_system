<?php
require_once 'const.php';

function st_session() {
    session_start();
    session_regenerate_id(true);
}

function check_login($sess_arr) {
    if (!isset($sess_arr[LOGIN])) {
//         header(LOCATION . get_host_and_dir() . '/../cmn_err/err.php?from=' . SYSTEM_TOP . ERR_MSG . 'ログインしていない');
        header(LOCATION . get_host_and_dir() . '/../staff_login/staff_login_err.php?from=' . SYSTEM_TOP . ERR_MSG . 'ログインしていない');
    }
}

function get_staff_id_and_logout() {
    return '<span>ログイン中のスタッフID：' . $_SESSION[STAFF_ID] . '</span><a href="' . get_host_and_dir() .'/../staff_login/staff_logout.php" style="margin-left: 1em;">ログアウト</a>';
}



//================================================================================
//関数名、引数名のチェック済st
//TODO:記載位置検討、定数化、

//TODO:全角スペース取り除き
function convert_sp_char_and_trim_rtn_arr($prm_target_arr) {
    $converted_arr = array();

    foreach ($prm_target_arr as $key => $val) {
        $converted_arr[$key] = trim(htmlspecialchars($val));
    }

    return $converted_arr;
}

//関数名、引数名のチェック済ed
//================================================================================

function check_unenter_item($prm_item_key_nm_arr, $prm_target_arr) {
    $err_msg = NULL;

    foreach ($prm_item_key_nm_arr as $key => $val) {
        if (strlen($prm_target_arr[$key]) === I_0) {
            $err_msg .= add_p($val . NOT_ENTERED) . LF;
        }
    }

    return $err_msg;
}

function check_unenter_unslct_item($prm_item_key_nm_arr, $prm_target_arr, $prm_unchk_key_nm_arr = NULL) {
    $err_msg = NULL;

    foreach ($prm_item_key_nm_arr as $key => $val) {
        if (isset( $prm_unchk_key_nm_arr) && in_array($key, $prm_unchk_key_nm_arr)) {
            continue;
        }else if (strpos($key, 'txt_') === I_0 && strlen($prm_target_arr[$key]) === I_0) {
            $err_msg .= add_p($val . NOT_ENTERED) . LF;
        } else if (strpos($key, 'slct_') === I_0 && intval($prm_target_arr[$key]) === I_0
            || strpos($key, 'rdo_') === I_0 && !isset($prm_target_arr[$key])) {
            $err_msg .= add_p($val . 'が未選択') . LF;
        }
    }

    return $err_msg;
}

function check_unenter_unslct_item_($prm_item_key_arr, $prm_item_name_arr, $prm_target_arr, $prm_unchk_key_nm_arr = NULL) {
    $err_msg = NULL;

    for ($i = 0; $i < count($prm_item_key_arr); $i++) {
        $key = $prm_item_key_arr[$i];

        if (isset($prm_unchk_key_nm_arr) && in_array($key, $prm_unchk_key_nm_arr)) {
            continue;
        }

        $target = $prm_target_arr[$key];
        $name = $prm_item_name_arr[$i];

        if (strpos($key, 'txt_') === I_0 && strlen($target) === I_0) {
            $err_msg .= add_p($name . NOT_ENTERED) . LF;
        } else if (strpos($key, 'slct_') === I_0 && intval($target) === I_0) {
            $err_msg .= add_p($name . 'が未選択') . LF;
        }
    }

    return $err_msg;
}

// function check_unenter_unslct_item_($prm_item_key_arr, $prm_item_name_arr, $prm_target_arr, $prm_unchk_key_nm_arr = NULL) {
//     $err_msg = NULL;

//     for ($i = 0; $i < count($prm_item_key_arr); $i++) {
//         if (isset($prm_unchk_key_nm_arr) && in_array($i, $prm_unchk_key_nm_arr)) {
//             continue;
//         }

//         $key = $prm_item_key_arr[$i];
//         $target = $prm_target_arr[$key];
//         $name = $prm_item_name_arr[$i];

//         if (strpos($key, 'txt_') === I_0 && strlen($target) === I_0) {
//             $err_msg .= add_p($name . NOT_ENTERED) . LF;
//         } else if (strpos($key, 'slct_') === I_0 && intval($target) === I_0) {
//             $err_msg .= add_p($name . 'が未選択') . LF;
//         }
//     }

//     return $err_msg;
// }




/**
 * 未入力の項目をチェックする
 *
 */
function check_unfilled_item_rtn_arr($item_txt_arr, $target_arr) {
    $val_arr = array();

    foreach ($item_txt_arr as $key => $val) {
        if (strlen($target_arr[$key]) === 0) {
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

    foreach ($item_key_arr as $key => $val) {
        if (array_key_exists($key, $target_arr)) {
            $val_arr[] = $target_arr[$key];
        }
    }

    return $val_arr;
}





function chk_alphanumeric($item_key_nm_arr, $target_arr) {
    $err_msg = NULL;

    foreach ($item_key_nm_arr as $key => $val) {
        if (! preg_match("/^[a-zA-Z0-9]+$/", $target_arr[$key])) {
            $err_msg .= add_p($val . 'に英数字以外が入力された') . LF; // TODO:定数化検討
        }
    }

    return $err_msg;
}






function check_alphanumeric($item_key_arr, $item_name_arr, $target_arr) {
    $err_msg = NULL;

//     foreach ($item_key_arr as $key => $val) {
//         if (!preg_match("/^[a-zA-Z0-9]+$/", $target_arr[$key])) {
//             $err_msg .= add_p($val . 'に英数字以外が入力された') . LF;//TODO:定数化検討
//         }
//     }

    for ($i = 0; $i < count($item_key_arr); $i++) {
        if (!preg_match("/^[a-zA-Z0-9]+$/", $target_arr[$item_key_arr[$i]])) {
            $err_msg .= add_p($item_name_arr[$i] . 'に英数字以外が入力された') . LF;//TODO:定数化検討
        }
    }



    return $err_msg;
}

//----------------------------------------
function get_age($prm_birth_date) {
    date_default_timezone_set('Asia/Tokyo');
    $current_date = intval(date('Ymd'));
    $birth_date = intval($prm_birth_date);

    return floor(($current_date - $birth_date) / 10000);
}




//----------------------------------------

// function build_opt_sex_01() {
//     $opt_arr = array('-', '男', '女', '未選択');

//     $opt_gender = build_opt_elem_with_selected(I_0, $opt_arr[I_0]);

//     for ($i = I_1; $i < count($opt_arr); $i++) {
//         $opt_gender .= build_opt_elem($i, $opt_arr[$i]);
//     }

//     return $opt_gender;
// }

function build_opt_sex($prm_slct_idx) {
    $opt_arr = array('-', '男', '女');

    $opt_gender = NULL;

    for ($i = I_0; $i < count($opt_arr); $i++) {
        if ($i == intval($prm_slct_idx)) {
            $opt_gender .= build_opt_elem_with_selected(I_1, $i, $opt_arr[$i]);
        } else {
            $opt_gender .= build_opt_elem(I_1, $i, $opt_arr[$i]);
        }
    }

    return $opt_gender;
}

// function build_opt_year() {
//     $opt_year = build_opt_elem_with_selected(I_0, '-');
//     $int_y = intval(date('Y'));

//     for ($i = $int_y - 16; $i >= $int_y - 90; $i--) {
//         $str_i = strval($i);
//         $opt_year .= build_opt_elem($str_i, $str_i);
//     }

//     return $opt_year;
// }

function build_opt_year($prm_slct_y) {
    $opt_year = NULL;
    $int_y = intval(date('Y'));

    if (isset($prm_slct_y)) {
        $opt_year = build_opt_elem(4, I_0, '-');

        for ($i = $int_y - 16; $i >= $int_y - 90; $i--) {
            if ($i == intval($prm_slct_y)) {
                $opt_year .= build_opt_elem_with_selected(4, strval($i), strval($i));
            } else {
                $opt_year .= build_opt_elem(4, strval($i), strval($i));
            }
        }
    } else {
        $opt_year = build_opt_elem_with_selected(4, I_0, '-');

        for ($i = $int_y - 16; $i >= $int_y - 90; $i--) {
            $opt_year .= build_opt_elem(4, strval($i), strval($i));
        }
    }

    return $opt_year;
}

function build_opt_month_day($prm_to, $prm_slct_m_or_d) {
    $opt_month_day = NULL;

    if (isset($prm_slct_m_or_d)) {
        $opt_month_day = build_opt_elem(I_2, I_0, '-');

        for ($i = I_1; $i <= $prm_to; $i++) {
            if ($i == intval($prm_slct_m_or_d)) {
                $opt_month_day .= build_opt_elem_with_selected(I_2, $i, $i);
            } else {
                $opt_month_day .= build_opt_elem(I_2, $i, $i);
            }
        }
    } else {
        $opt_month_day = build_opt_elem_with_selected(I_2, I_0, '-');

        for ($i = I_1; $i <= $prm_to; $i++) {
            $opt_month_day .= build_opt_elem(I_2, $i, $i);
        }
    }

    return $opt_month_day;
}


//----------------------------------------

function add_p($prm_content) {
    return '<p>' . $prm_content . '</p>';
}

function add_div($prm_content) {
    return '<div>' . $prm_content . '</div>';
}

//TODO:関数内で例外を補足せず、関数の使用側で補足する方向で
function execute_sql_rtn_PDOStatement($statement, $input_parameter_arr) {
    $dbh = new PDO('mysql:dbname=y240608_01;host=localhost', 'root', '');
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

// 24/06/29以降に記載した関数 ST ----------------------------------------

function is_bf_change_temp_pswd($prm_crnt_pswd, $prm_temp_pswd) {
    return !isset($prm_crnt_pswd) && isset($prm_temp_pswd);
}




// 24/06/29以降に記載した関数 ED ----------------------------------------











/*--------------------------------------------------------------------------------
以下private関数
--------------------------------------------------------------------------------*/
function get_host_and_dir() {
    return HTTP . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
}


// function build_opt_elem($prm_val, $prm_content) {
//     return '<option value="' . sprintf('%02d', $prm_val) . '">' . $prm_content . '</option>' . LF;
// }

// function build_opt_elem_with_selected($prm_val, $prm_content) {
//     return '<option value="' . sprintf('%02d', $prm_val) . '" selected="selected">' . $prm_content . '</option>' . LF;
// }

function build_opt_elem($prm_format, $prm_val, $prm_content) {
    return '<option value="' . sprintf('%0' . $prm_format . 'd', $prm_val) . '">' . $prm_content . '</option>' . LF;
}

function build_opt_elem_with_selected($prm_format, $prm_val, $prm_content) {
    return '<option value="' . sprintf('%0' . $prm_format . 'd', $prm_val) . '" selected="selected">' . $prm_content . '</option>' . LF;
}



?>
