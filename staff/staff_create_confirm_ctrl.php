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
        'slct_sex' => '性別',                     // 4
        'slct_birth_year' => '生年月日の年',      // 5
        'slct_birth_month' => '生年月日の月',     // 6
        'slct_birth_day' => '生年月日の日',       // 7
        'txt_password_1' => 'パスワード',        // 8
        'txt_password_2' => 'パスワード（確認）' // 9
    );

    // 入力値のサニタイズと空白文字の除去
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

//     // 氏名のKeyとValue
//     $name_key_nm_arr = array_slice($item_key_nm_arr, I_0, 4);
//     // パスワードのKeyとValue
//     $pswd_key_nm_arr = array_slice($item_key_nm_arr, 8, I_2);

//     // 入力値、選択値のチェック：ST ----------

//     // 未入力、未選択の項目のチェック
//     // テキスト入力項目（0, 1, 2, 3, 8, 9）
//     $txt_item_key_nm_arr = array_merge($name_key_nm_arr, $pswd_key_nm_arr);
//     // セレクト選択項目（4, 5, 6, 7）
//     $slct_item_key_nm_arr = array_slice($item_key_nm_arr, 4, 4);

//     $empty_msg_arr = array_merge(check_unfilled_item_rtn_arr($txt_item_key_nm_arr, $item_val_arr),
//         check_unselected_item_rtn_arr($slct_item_key_nm_arr, $item_val_arr));

    $empty_msg_arr = chk_unent_unslct_item_rtn_arr($item_key_nm_arr, $item_val_arr);

    if (count($empty_msg_arr) > 0) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return build_err_content($empty_msg_arr);
    }

    $invalid_msg_arr = array();

    // 生年月日の妥当性のチェック
    if (!checkdate(intval($item_val_arr['birth_month']), intval($item_val_arr['birth_day']), intval($item_val_arr['birth_year']))) {
        $invalid_msg_arr[] = '生年月日が不正';
    }

    // パスワードのチェック
    // パスワードの文字種のチェック
    //TODO:24年05月19日時点のパスワードの扱い：前後の半角スペースはチェック前に削除された状態
    $checked_alphanumeric_msg_arr = check_alphanumeric_rtn_arr($pswd_key_nm_arr, $item_val_arr);

    if (count($checked_alphanumeric_msg_arr) > 0) {
        // パスワードの文字種に不備がある場合
//         $invalid_msg_arr = array_merge($invalid_msg_arr, $checked_alphanumeric_msg_arr);
        foreach ($checked_alphanumeric_msg_arr as $val) {
            $invalid_msg_arr[] = $val;
        }
    } else if (strcmp($item_val_arr['password_1'], $item_val_arr['password_2']) !== 0) {
        // パスワードとパスワード（確認）が一致しない場合
        $invalid_msg_arr[] = $item_key_nm_arr['password_1'] . 'と' . $item_key_nm_arr['password_2'] . 'が一致しない';
    }

    if (count($invalid_msg_arr) > 0) {
        // 生年月日とパスワードのどちらか、もしくは双方ともに不備がある場合、エラーメッセージを表示する
        return build_err_content($invalid_msg_arr);
    }

    // 入力値、選択値のチェック：ED ----------

    $hidden_arr = array($item_val_arr['last_name'],
        $item_val_arr['first_name'],
        $item_val_arr['last_name_kana'],
        $item_val_arr['first_name_kana'],
        $sex_arr[intval($item_val_arr['sex'])],
        $birth_date,
        '********');

    $sex_arr = array('-', '男', '女', '未選択');
    $birth_date = $item_val_arr['birth_year'] . '年' . $item_val_arr['birth_month'] . '月' . $item_val_arr['birth_day'] . '日';
    $table_element = build_table_element(array('氏', '名', '氏（カナ）', '名（カナ）', '性別', '生年月日', 'パスワード'), $hidden_arr);
//     $tmp_arr =

    $content_arr = array_slice($item_val_arr, I_0, );




    return <<<EOS
<p>下記の内容で問題なければ実行ボタンを押す</p>
{$table_element}
<form method="post" action="./staff_create_done.php">
<input type="submit" value="実行">
</form>

EOS;

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

function build_table_element($prm_heading_arr, $prm_value_arr) {
    $table_element = '<table>' . LF;

    for ($i = 0; $i < count($prm_heading_arr); $i++) {
        $table_element .= '<tr><td>' . $prm_heading_arr[$i] . '</td><td>：' . $prm_value_arr[$i] . '</td></tr>' . LF;
    }

    $table_element .= '</table>' . LF;

    return $table_element;
}






?>
