<?php
require_once 'const.php';

function st_session() {
    session_start();
    session_regenerate_id(true);
}

function check_login($session_arr) {
    if (!isset($session_arr[LOGIN])) {
        header(LOCATION . get_host_and_dir() . '/../staff_login/staff_login_err.php?from=' . SYSTEM_TOP . '&err_msg=' . S_LOGIN . 'していない');
    }
}

function get_staff_id_and_logout() {
    return '<span>' . S_LOGIN . '中の' . S_STAFF . U_ID . S_COLON . $_SESSION[STAFF_ID] . '</span><a href="'
        . get_host_and_dir() .'/../staff_login/staff_logout.php" style="margin-left: 1em;">ログアウト</a>';
}

// 「staff_login」ディレクトリ内から参照される関数 ST ----------------------------------------TODO:ディレクトリ以外の表現調査

//TODO:全角スペース取り除き
function convert_sp_char_and_trim_rtn_arr($prm_target_arr) {
    $converted_arr = array();

    foreach ($prm_target_arr as $key => $val) {
        $converted_arr[$key] = trim(htmlspecialchars($val));
    }

    return $converted_arr;
}

// 「staff_login」ディレクトリ内から参照される関数 ED ----------------------------------------

// 「staff」ディレクトリ内から参照される関数 ST ----------------------------------------

const P_CHECKED = ' checked';

function get_tbl_elem($prm_staff_data_arr) {
    $id_content = NULL;
    $staff_data_arr = NULL;

    if (isset($prm_staff_data_arr)) {
        $id_content =<<<EOI

<tr>
<td>スタッフID</td><td class="p-l-5">{$prm_staff_data_arr[ID]}</td>
</tr>
EOI;
        $staff_data_arr = $prm_staff_data_arr;
    } else {
        $id_content = EMPTY_STR;
        $staff_data_arr = array(
            LAST_NAME => EMPTY_STR
            , FIRST_NAME => EMPTY_STR
            , LAST_NAME. KANA => EMPTY_STR
            , FIRST_NAME . KANA => EMPTY_STR
            , SEX => I_0
            , BIRTH_YEAR => NULL
           , BIRTH_MONTH => NULL
           , BIRTH_DAY => NULL
           , PRIVILEGE => NULL
        );
    }

    $opt_sex = build_opt_sex($staff_data_arr[SEX]);
    $opt_year = build_opt_year($staff_data_arr[BIRTH_YEAR]);
    $opt_month = build_opt_month_day(12, $staff_data_arr[BIRTH_MONTH]);
    $opt_day = build_opt_month_day(31, $staff_data_arr[BIRTH_DAY]);

    $privilege_checked_o = NULL;
    $privilege_checked_a = NULL;

    switch ($staff_data_arr[PRIVILEGE]) {
        case PRIVILEGE_O:
            $privilege_checked_o = P_CHECKED;
            $privilege_checked_a = EMPTY_STR;
            break;

        case PRIVILEGE_A:
            $privilege_checked_o = EMPTY_STR;
            $privilege_checked_a = P_CHECKED;
            break;

        default:
            $privilege_checked_o = EMPTY_STR;
            $privilege_checked_a = EMPTY_STR;
            break;
    }

    return <<<EOC
<table>{$id_content}
<tr>
<td>氏</td><td><input type="text" name="last_name" value="{$staff_data_arr[LAST_NAME]}" class="w-100"></td>
</tr>
<tr>
<td>名</td><td><input type="text" name="first_name" value="{$staff_data_arr[FIRST_NAME]}" class="w-100"></td>
</tr>
<tr>
<td>氏（カナ）</td><td><input type="text" name="last_name_kana" value="{$staff_data_arr[LAST_NAME. KANA]}" class="w-100"></td>
</tr>
<tr>
<td>名（カナ）</td><td><input type="text" name="first_name_kana" value="{$staff_data_arr[FIRST_NAME . KANA]}" class="w-100"></td>
</tr>
<tr>
<td>性別</td><td><select name="sex">
{$opt_sex}</select></td>
</tr>
<tr>
<td>生年月日</td><td><select name="birth_year">
{$opt_year}</select>年
<select name="birth_month">
{$opt_month}</select>月
<select name="birth_day">
{$opt_day}</select>日</td>
</tr>
<tr>
<td>権限</td><td><span><input type="radio" name="privilege" id="ordinary" value="O"{$privilege_checked_o}><label for="ordinary">一般</label></span>
<span class="m-l-10"><input type="radio" name="privilege" id="administrator" value="A"{$privilege_checked_a}><label for="administrator">管理者</label></span></td>
</tr>
</table>
EOC;
}


// 「staff」ディレクトリ内から参照される関数 ED ----------------------------------------



//TODO:以上記載位置整理済み以下未整理















//----------------------------------------




//----------------------------------------


function build_opt_sex($prm_slct_idx) {
    $opt_arr = array(HYPHEN, S_OTOKO, S_ONNA);

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


function build_opt_year($prm_slct_y) {
    $opt_year = NULL;
    $int_y = intval(date('Y'));

    if (isset($prm_slct_y)) {
        $opt_year = build_opt_elem(4, I_0, HYPHEN);

        for ($i = $int_y - 16; $i >= $int_y - 90; $i--) {
            if ($i == intval($prm_slct_y)) {
                $opt_year .= build_opt_elem_with_selected(4, strval($i), strval($i));
            } else {
                $opt_year .= build_opt_elem(4, strval($i), strval($i));
            }
        }
    } else {
        $opt_year = build_opt_elem_with_selected(4, I_0, HYPHEN);

        for ($i = $int_y - 16; $i >= $int_y - 90; $i--) {
            $opt_year .= build_opt_elem(4, strval($i), strval($i));
        }
    }

    return $opt_year;
}

function build_opt_month_day($prm_to, $prm_slct_m_or_d) {
    $opt_month_day = NULL;

    if (isset($prm_slct_m_or_d)) {
        $opt_month_day = build_opt_elem(I_2, I_0, HYPHEN);

        for ($i = I_1; $i <= $prm_to; $i++) {
            if ($i == intval($prm_slct_m_or_d)) {
                $opt_month_day .= build_opt_elem_with_selected(I_2, $i, $i);
            } else {
                $opt_month_day .= build_opt_elem(I_2, $i, $i);
            }
        }
    } else {
        $opt_month_day = build_opt_elem_with_selected(I_2, I_0, HYPHEN);

        for ($i = I_1; $i <= $prm_to; $i++) {
            $opt_month_day .= build_opt_elem(I_2, $i, $i);
        }
    }

    return $opt_month_day;
}


//TODO:HTML関連の関数の仮記載位置(From24/10/03)↓↓↓↓↓↓↓↓↓↓

function add_p($prm_content) {
    return TS_P . $prm_content . TE_P;
}

function add_div($prm_content) {
    return '<div>' . $prm_content . '</div>';
}

function process_month_and_day($prm_str) {
    if (strcmp(substr($prm_str, I_0, I_1), S_0) === 0) {
        return substr($prm_str, I_1);
    }

    return $prm_str;
}

//TODO:HTML関連の関数の仮記載位置(From24/10/03)↑↑↑↑↑↑↑↑↑↑



/*--------------------------------------------------------------------------------
以下private関数
--------------------------------------------------------------------------------*/
function get_host_and_dir() {
    return 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
}



function build_opt_elem($prm_format, $prm_val, $prm_content) {
    return '<option value="' . sprintf('%0' . $prm_format . 'd', $prm_val) . '">' . $prm_content . '</option>' . LF;
}

function build_opt_elem_with_selected($prm_format, $prm_val, $prm_content) {
    return '<option value="' . sprintf('%0' . $prm_format . 'd', $prm_val) . '" selected="selected">' . $prm_content . '</option>' . LF;
}



?>
