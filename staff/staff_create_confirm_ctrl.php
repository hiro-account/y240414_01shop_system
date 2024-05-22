<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

function get_content($prm_post) {
    //TODO:配列の要素、配置位置要整理
    $item_key_nm_arr = array(
        'txt_last_name' => '氏',                 // 0
        'txt_first_name' => '名',                // 1
        'txt_last_name_kana' => '氏（カナ）',    // 2
        'txt_first_name_kana' => '名（カナ）',   // 3
        'slct_sex' => '性別',                    // 4
        'slct_birth_year' => '生年月日の年',     // 5
        'slct_birth_month' => '生年月日の月',    // 6
        'slct_birth_day' => '生年月日の日',      // 7
        'txt_password_1' => 'パスワード',        // 8
        'txt_password_2' => 'パスワード（確認）' // 9
    );

    // 入力値のサニタイズと空白文字の除去
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 入力値、選択値のチェック：ST ----------

    // 未入力、未選択の項目のチェック
    $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array('slct_sex'));

    if (isset($empty_msg)) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return $empty_msg;
    }

    $invalid_msg = NULL;

    // 生年月日の妥当性のチェック
    if (!checkdate(intval($item_val_arr['slct_birth_month']), intval($item_val_arr['slct_birth_day']), intval($item_val_arr['slct_birth_year']))) {
        $invalid_msg .= add_p('生年月日が不正') . LF;
    }

    // パスワードのチェック
    // パスワードの文字種のチェック
    //TODO:24年05月19日時点のパスワードの扱い：前後の半角スペースはチェック前に削除された状態
    $offset = 8;
    $checked_alphanumeric_msg = check_alphanumeric(array_slice($item_key_nm_arr, $offset), array_slice($item_val_arr, $offset));

    if (isset($checked_alphanumeric_msg)) {
        // パスワードの文字種に不備がある場合
        $invalid_msg .= $checked_alphanumeric_msg;
    } else if (strcmp($item_val_arr['txt_password_1'], $item_val_arr['txt_password_2']) !== 0) {
        // パスワードとパスワード（確認）が一致しない場合
        $invalid_msg .= add_p($item_key_nm_arr['txt_password_1'] . 'と' . $item_key_nm_arr['txt_password_2'] . 'が一致しない') . LF;
    }

    if (isset($invalid_msg)) {
        // 生年月日とパスワードのどちらか、もしくは双方ともに不備がある場合、エラーメッセージを表示する
        return $invalid_msg;
    }

    // 入力値、選択値のチェック：ED ----------

    //24/05/23：次回関数にcontinueするキーを渡す方法検討から

    //
    $sex_arr = array('-', '男', '女', '未選択');
    $birth_date = $item_val_arr['slct_birth_year'] . '年' . $item_val_arr['slct_birth_month'] . '月' . $item_val_arr['slct_birth_day'] . '日';
    $heading_arr = array_merge(array_slice($item_key_nm_arr, I_0, 5), array('birth_date' => '生年月日', 'password' => $item_key_nm_arr['txt_password_1']));
    $content_arr = array_merge(array_slice($item_val_arr, I_0, 4),
        array('slct_sex' => $sex_arr[intval($item_val_arr['slct_sex'])], 'birth_date' => $birth_date, 'password' => '非表示'));
    $tbl_elem = build_tbl_elem($heading_arr, $content_arr);

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

function build_tbl_elem($prm_heading_arr, $prm_value_arr) {
    $table_element = '<table>' . LF;

    foreach ($prm_heading_arr as $key => $val) {
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
