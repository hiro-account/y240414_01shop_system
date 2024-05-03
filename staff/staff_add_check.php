<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

// $item_name_arr = array(STAFF_NAME => 'スタッフ名', STAFF_PASS => 'パスワード', STAFF_PASS2 => 'パスワード(確認)');
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
$header = ERR_HEADER . STAFF_ADD_CHECK . ERR_MSG;

$posted_arr = convert_sp_chara_rtn_arr($_POST);

// 未入力、未選択の項目のチェック
// $checked_unfilled_msg_arr = check_unfilled_item_rtn_arr($item_txt_arr, $posted_arr);

$merged_msg_arr = array_merge(check_unfilled_item_rtn_arr($item_txt_arr, $posted_arr),
    check_unselected_item_rtn_arr($item_slct_arr, $posted_arr));

if (count($merged_msg_arr) > 0) {
    // 未入力、未選択の項目がある場合、エラーページに遷移する
    header($header . implode(DELIMITER, sort_msg_rtn_arr($item_key_arr, $merged_msg_arr)));
    exit();
}

// 生年月日の妥当性のチェック
if (!checkdate(intval($posted_arr['birth_month']), intval($posted_arr['birth_day']), intval($posted_arr['birth_year']))) {
    header($header . '生年月日が不正');
    exit();
}



// パスワードの文字種のチェック
$checked_alphanumeric_msg_arr = check_alphanumeric_rtn_arr(array_slice($item_name_arr, 1), $posted_arr);

if (count($checked_alphanumeric_msg_arr) > 0) {
    // 英数字以外の項目がある場合、エラーページに遷移する
    header($header . implode(DELIMITER, $checked_alphanumeric_msg_arr));
    exit();
}

$staff_pass = $posted_arr[STAFF_PASS];

// パスワードの一致のチェック
if (strcmp($staff_pass, $posted_arr[STAFF_PASS2]) != 0) {
    // パスワードとパスワード(確認)が一致しない場合、エラーページに遷移する
    header($header . $item_name_arr[STAFF_PASS] . 'と' . $item_name_arr[STAFF_PASS2] . 'が一致しない');
    exit();
}

try {
    executeSql('INSERT INTO mst_staff (name, password) VALUES (?, ?)', array($posted_arr[STAFF_NAME], md5($staff_pass)));
} catch (Exception $e) {
    header($header . 'システム障害発生中');
    exit();
}

// session_start();
// $_SESSION[LOGIN] = LOGIN;
// $_SESSION[STAFF_ID] = $staff_id;
header('Location: ../system/system_top.php');
?>
