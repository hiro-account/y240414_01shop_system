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

$posted_arr = convert_sp_char_and_trim_rtn_arr($_POST);

// 未入力、未選択の項目のチェック
// $checked_unfilled_msg_arr = check_unfilled_item_rtn_arr($item_txt_arr, $posted_arr);

$empty_msg_arr = array_merge(check_unfilled_item_rtn_arr($item_txt_arr, $posted_arr),
    check_unselected_item_rtn_arr($item_slct_arr, $posted_arr));

if (count($empty_msg_arr) > 0) {
    // 未入力、未選択の項目がある場合、エラーページに遷移する
    header($header . implode(DELIMITER, sort_msg_rtn_arr($item_key_arr, $empty_msg_arr)));
    exit();
}

$invalid_msg_arr = array();

// 生年月日の妥当性のチェック
if (!checkdate(intval($posted_arr['birth_month']), intval($posted_arr['birth_day']), intval($posted_arr['birth_year']))) {
//     header($header . '生年月日が不正');
//     exit();
    $invalid_msg_arr[] = '生年月日が不正';
}

// パスワードの文字種のチェック
$checked_alphanumeric_msg_arr = check_alphanumeric(array_slice($item_txt_arr, 4), $posted_arr);

if (count($checked_alphanumeric_msg_arr) > 0) {
//     // 英数字以外の項目がある場合、エラーページに遷移する
//     header($header . implode(DELIMITER, $checked_alphanumeric_msg_arr));
//     exit();
    $invalid_msg_arr[] = implode(DELIMITER, $checked_alphanumeric_msg_arr);
} else if (strcmp($posted_arr['password_1'], $posted_arr['password_2']) !== 0) {
    $invalid_msg_arr[] = $item_txt_arr['password_1'] . 'と' . $item_txt_arr['password_2'] . 'が一致しない';
}

if (count($invalid_msg_arr) > 0) {
    header($header . implode(DELIMITER, $invalid_msg_arr));
    exit();
}

$input_parameter_arr = array($posted_arr['last_name'],
    $posted_arr['first_name'],
    $posted_arr['last_name_kana'],
    $posted_arr['first_name_kana'],
    intval($posted_arr['sex']),
    $posted_arr['birth_year'] . '-' . sprintf('%02d', $posted_arr['birth_month']) . '-' . sprintf('%02d', $posted_arr['birth_day']),
    md5($posted_arr['password_1']));

try {
    execute_sql_rtn_PDOStatement('INSERT INTO mst_staff '
        . '(last_name, first_name, last_name_kana, first_name_kana, sex, birthday, password) '
        . 'VALUES (?, ?, ?, ?, ?, ?, ?)',
        $input_parameter_arr);
} catch (Exception $e) {
    header($header . 'システム障害発生中');
    exit();
}

// session_start();
// $_SESSION[LOGIN] = LOGIN;
// $_SESSION[STAFF_ID] = $staff_id;
header('Location: ../system/system_top.php');
?>
