<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

function get_content($prm_post) {
    $item_key_arr = array('last_name',
        'first_name',
        'last_name_kana',
        'first_name_kana',
        'sex',
        'birth_year',
        'birth_month',
        'birth_day',
        'password_1',
        'password_2',
    );
    $item_txt_arr = array('last_name' => '氏',
        'first_name' => '名',
        'last_name_kana' => '氏（カナ）',
        'first_name_kana' => '名（カナ）',
        'password_1' => 'パスワード',
        'password_2' => 'パスワード（確認）'
    );
    $item_slct_arr = array('sex' => '性別',
        'birth_year' => '生年月日の年',
        'birth_month' => '生年月日の月',
        'birth_day' => '生年月日の日',
    );

    //
    $posted_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 未入力、未選択の項目のチェック
    $empty_msg_arr = array_merge(check_unfilled_item_rtn_arr($item_txt_arr, $posted_arr),
        check_unselected_item_rtn_arr($item_slct_arr, $posted_arr));

    if (count($empty_msg_arr) > 0) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return build_err_content(sort_msg_rtn_arr($item_key_arr, $empty_msg_arr));
    }

    $invalid_msg_arr = array();

    // 生年月日の妥当性のチェック
    if (!checkdate(intval($posted_arr['birth_month']), intval($posted_arr['birth_day']), intval($posted_arr['birth_year']))) {
        $invalid_msg_arr[] = '生年月日が不正';
    }

    // パスワードのチェック
    // パスワードの文字種のチェック
    $checked_alphanumeric_msg_arr = check_alphanumeric_rtn_arr(array_slice($item_txt_arr, 4), $posted_arr);

    if (count($checked_alphanumeric_msg_arr) > 0) {
        // パスワードの文字種に不備がある場合
        $invalid_msg_arr[] = implode(DELIMITER, $checked_alphanumeric_msg_arr);
    } else if (strcmp($posted_arr['password_1'], $posted_arr['password_2']) !== 0) {
        // パスワードとパスワード（確認）が一致しない場合
        $invalid_msg_arr[] = $item_txt_arr['password_1'] . 'と' . $item_txt_arr['password_2'] . 'が一致しない';
    }

    if (count($invalid_msg_arr) > 0) {
        // 生年月日とパスワードのどちらか、もしくは双方ともに不備がある場合、エラーメッセージを表示する
        return build_err_content($invalid_msg_arr);
    }


}

function build_err_content($prm_err_msg_arr) {
    $content = NULL;

    for ($i = 0; $i < count($prm_err_msg_arr); $i++) {
        $content .= add_p($prm_err_msg_arr[$i]) . LF;
    }

    return $content;
}








?>
