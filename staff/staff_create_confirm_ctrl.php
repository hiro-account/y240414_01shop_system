<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'temp_const.php');

function get_content($prm_post) {
    //TODO:配列の要素、配置位置要整理
    $item_key_nm_arr = array(
        'txt_last_name' => LAST_NAME,               // 0
        'txt_first_name' => FIRST_NAME,             // 1
        'txt_last_name_kana' => LAST_NAME . KANA,   // 2
        'txt_first_name_kana' => FIRST_NAME . KANA, // 3
        'slct_sex' => SEX,                          // 4
        'sex' => SEX,                               // 5
        'slct_birth_year' => BIRTH_DATE . YEAR,     // 6
        'slct_birth_month' => BIRTH_DATE . MONTH,   // 7
        'slct_birth_day' => BIRTH_DATE . DAY,       // 8
        'birth_date' => BIRTH_DATE,                 // 9
        'txt_password_1' => PASSWORD,               // 10
        'txt_password_2' => PASSWORD . CONFIRM,     // 11
        'password' => PASSWORD,                     // 12
    );

    $item_key_arr = array (
        'txt_last_name',        // 0
        'txt_first_name',       // 1
        'txt_last_name_kana',   // 2
        'txt_first_name_kana',  // 3
        'slct_sex',             // 4
        'sex',                  // 5
        'slct_birth_year',      // 6
        'slct_birth_month',     // 7
        'slct_birth_day',       // 8
        'birth_date',           // 9
        'txt_password_1',       // 10
        'txt_password_2',       // 11
        'password'              // 12
    );

    $item_name_arr = array(
        LAST_NAME,          // 0
        FIRST_NAME,         // 1
        LAST_NAME . KANA,   // 2
        FIRST_NAME . KANA,  // 3
        SEX,                // 4
        SEX,                // 5
        BIRTH_DATE . YEAR,  // 6
        BIRTH_DATE . MONTH, // 7
        BIRTH_DATE . DAY,   // 8
        BIRTH_DATE,         // 9
        PASSWORD,           // 10
        PASSWORD . CONFIRM, // 11
        PASSWORD            // 12
    );

    $sex_arr = array('-', '男', '女');


    // 入力値のサニタイズと空白文字の除去
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 入力値、選択値のチェック：ST ----------

    // 未入力、未選択の項目のチェック
//     $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array('slct_sex'));
//     $empty_msg = check_unenter_unslct_item_($item_key_arr, $item_name_arr, $item_val_arr, array('slct_sex', 'sex', 'birth_date', 'password'));
    $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array('slct_sex', 'sex', 'birth_date', 'password'));




    if (isset($empty_msg)) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return $empty_msg;
    }

    $invalid_msg = NULL;

    // 生年月日の妥当性のチェック
    if (!checkdate(intval($item_val_arr['slct_birth_month']), intval($item_val_arr['slct_birth_day']), intval($item_val_arr['slct_birth_year']))) {
        $invalid_msg .= add_p(BIRTH_DATE . 'が不正') . LF;
    }

    // パスワードのチェック
    // パスワードの文字種のチェック
    //TODO:24年05月19日時点のパスワードの扱い：前後の半角スペースはチェック前に削除された状態
    $offset = 10;
    $length = I_2;
//     $checked_alphanumeric_msg = check_alphanumeric(array_slice($item_key_arr, $offset, $length), array_slice($item_name_arr, $offset, $length), array_slice($item_val_arr, 8));
    $checked_alphanumeric_msg = chk_alphanumeric(array_slice($item_key_nm_arr, $offset, $length), array_slice($item_val_arr, 8));

    if (isset($checked_alphanumeric_msg)) {
        // パスワードの文字種に不備がある場合
        $invalid_msg .= $checked_alphanumeric_msg;
    } else if (strcmp($item_val_arr['txt_password_1'], $item_val_arr['txt_password_2']) !== 0) {
        // パスワードとパスワード（確認）が一致しない場合
        $invalid_msg .= add_p(PASSWORD . 'と' . PASSWORD . CONFIRM . 'が一致しない') . LF;
    }

    if (isset($invalid_msg)) {
        // 生年月日とパスワードのどちらか、もしくは双方ともに不備がある場合、エラーメッセージを表示する
        return $invalid_msg;
    }

    // 入力値、選択値のチェック：ED ----------

    //24/05/23：次回関数にcontinueするキーを渡す方法検討から

    //


    $content_arr = array_slice($item_val_arr, I_0, 4);
    $content_arr['sex'] = $sex_arr[intval($item_val_arr['slct_sex'])];
    $content_arr['birth_date'] = $item_val_arr['slct_birth_year'] . '年' . $item_val_arr['slct_birth_month'] . '月' . $item_val_arr['slct_birth_day'] . '日';
    $content_arr['password'] = '非表示';

//     $tbl_elem = build_tbl_elem($heading_arr, $content_arr);
//     $tbl_elem = bld_tbl_elem($item_key_arr, $item_name_arr, $content_arr, array('slct_sex', 'slct_birth_year','slct_birth_month','slct_birth_day', 'txt_password_1', 'txt_password_2'));
    $tbl_elem = build_tbl_elem($item_key_nm_arr, $content_arr, array('slct_sex', 'slct_birth_year','slct_birth_month','slct_birth_day', 'txt_password_1', 'txt_password_2'));
    $hdn_elem = build_hdn_elem(array_merge(array_slice($item_val_arr, I_0, 8), array('password' => password_hash($item_val_arr['txt_password_1'], PASSWORD_BCRYPT))));





    return <<<EOE
<p>下記の内容で問題なければ実行ボタンを押す</p>
{$tbl_elem}
<form method="post" action="./staff_create_done.php">
{$hdn_elem}<p><input type="submit" value="実行"></p>
</form>

EOE;

//     return '<p>下記の内容で問題なければ実行ボタンを押す</p>' . LF .
//         build_table_element(array('氏', '名', '氏（カナ）', '名（カナ）', '性別', '生年月日', 'パスワード'),
//             array($posted_arr['last_name'],
//                 $posted_arr['first_name'],
//                 $posted_arr['last_name_kana'],
//                 $posted_arr['first_name_kana'],
//                 $sex_arr[intval($posted_arr['sex'])],
//                 $birth_date, '********'));
}

function build_err_content($prm_err_msg_arr) {
    $content = NULL;

//     for ($i = 0; $i < count($prm_err_msg_arr); $i++) {
//         $content .= add_p($prm_err_msg_arr[$i]) . LF;
//     }
    foreach ($prm_err_msg_arr as $value) {
        $content .= add_p($value);
    }

    return $content;
}

function bld_tbl_elem($prm_item_key_arr, $prm_item_name_arr, $prm_target_arr, $prm_unchk_key_nm_arr = NULL) {
    $table_element = '<table>' . LF;

    for ($i = 0; $i < count($prm_item_key_arr); $i++) {
        $key = $prm_item_key_arr[$i];

        if (isset($prm_unchk_key_nm_arr) && in_array($key, $prm_unchk_key_nm_arr)) {
            continue;
        }

        $table_element .= '<tr><td>' . $prm_item_name_arr[$i] . '</td><td>：' . $prm_target_arr[$key] . '</td></tr>' . LF;
    }

    $table_element .= '</table>';

    return $table_element;
}



function build_tbl_elem($prm_heading_arr, $prm_value_arr, $prm_unchk_key_nm_arr = NULL) {
    $table_element = '<table>' . LF;

    foreach ($prm_heading_arr as $key => $val) {
        if (isset($prm_unchk_key_nm_arr) && in_array($key, $prm_unchk_key_nm_arr)) {
            continue;
        }

        $table_element .= '<tr><td>' . $val . '</td><td>：' . $prm_value_arr[$key] . '</td></tr>' . LF;
    }

    $table_element .= '</table>';

    return $table_element;
}

function build_hdn_elem($prm_target_arr) {
    $hdn_elem = NULL;

    foreach ($prm_target_arr as $key => $val) {
        $hdn_elem .= '<input type="hidden" name="' . $key . '" value="' . $val . '">' . LF;
    }

    return $hdn_elem;
}


function build_table_element($prm_heading_arr, $prm_value_arr) {
    $table_element = '<table>' . LF;

    for ($i = 0; $i < count($prm_heading_arr); $i++) {
        $table_element .= '<tr><td>' . $prm_heading_arr[$i] . '</td><td>：' . $prm_value_arr[$i] . '</td></tr>' . LF;
    }

    $table_element .= '</table>' . LF;

    return $table_element;
}
?>
