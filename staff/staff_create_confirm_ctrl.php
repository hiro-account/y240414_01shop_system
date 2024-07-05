<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'temp_const.php');

//TODO:住所、電話番号、電子メールアドレスの追加
function get_content($prm_post) {
    //TODO:配列の要素、配置位置要整理
    $item_key_nm_arr = array(
        'txt_last_name' => LAST_NAME                    // 0
        , 'txt_first_name' => FIRST_NAME                // 1
        , 'txt_last_name_kana' => LAST_NAME . KANA      // 2
        , 'txt_first_name_kana' => FIRST_NAME . KANA    // 3
        , 'slct_sex' => SEX                             // 4
        , 'sex' => SEX                                  // 5
        , 'slct_birth_year' => BIRTH_DATE . YEAR        // 6
        , 'slct_birth_month' => BIRTH_DATE . MONTH      // 7
        , 'slct_birth_day' => BIRTH_DATE . DAY          // 8
        , 'birth_date' => BIRTH_DATE                    // 9
        , 'rdo_privilege' => '権限'                     // 10
    );

    $sex_arr = array('-', '男', '女');
    $privilege_arr = array('O' => '一般', 'A' => '管理者');

    // 入力値のサニタイズと空白文字の除去
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 入力値、選択値のチェック：ST ----------
    // 未入力、未選択の項目のチェック
    $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array('slct_sex', 'sex', 'birth_date'));

    if (isset($empty_msg)) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return $empty_msg;
    }

    //TODO:パスワードのチェックを削除し生年月日のチェックだけとなったため、エラーメッセージ表示の処理の更新を検討
    $invalid_msg = NULL;

    // 生年月日の妥当性のチェック
    if (!checkdate(intval($item_val_arr['slct_birth_month']), intval($item_val_arr['slct_birth_day']), intval($item_val_arr['slct_birth_year']))) {
        $invalid_msg .= add_p(BIRTH_DATE . 'が不正') . LF;
    }

    if (isset($invalid_msg)) {
        // 生年月日とパスワードのどちらか、もしくは双方ともに不備がある場合、エラーメッセージを表示する
        return $invalid_msg;
    }

    // 入力値、選択値のチェック：ED 画面表示項目、hidden項目の設定 ST ----------

    // 画面表示項目
    $cont_arr = array_slice($item_val_arr, I_0, 4);
    $cont_arr['sex'] = $sex_arr[intval($item_val_arr['slct_sex'])];
    $cont_arr['birth_date'] = $item_val_arr['slct_birth_year'] . '年' . $item_val_arr['slct_birth_month'] . '月' . $item_val_arr['slct_birth_day'] . '日';
    $cont_arr['rdo_privilege'] = $privilege_arr[$item_val_arr['rdo_privilege']];
//     $cont_arr['password'] = '非表示';
    $tbl_elem = build_tbl_elem($item_key_nm_arr, $cont_arr, array('slct_sex', 'slct_birth_year','slct_birth_month','slct_birth_day'/*, 'txt_password_1', 'txt_password_2'*/));

    // hidden項目
    $hdn_elem = build_hdn_elem($item_val_arr);

    return <<<EOE
<p>下記の内容で問題なければ実行ボタンを押す</p>
{$tbl_elem}
<form method="post" action="./staff_create_done.php">
{$hdn_elem}<p><input type="submit" value="実行"></p>
</form>

EOE;
}

function build_err_content($prm_err_msg_arr) {
    $content = NULL;

    foreach ($prm_err_msg_arr as $value) {
        $content .= add_p($value);
    }

    return $content;
}

function build_tbl_elem($prm_item_key_name_arr, $prm_content_arr, $prm_continue_key_arr = NULL) {
    $tbl_elem = '<table>' . LF;

    foreach ($prm_item_key_name_arr as $key => $val) {
        if (isset($prm_continue_key_arr) && in_array($key, $prm_continue_key_arr)) {
            continue;
        }

        $tbl_elem .= '<tr><td>' . $val . '</td><td>：' . $prm_content_arr[$key] . '</td></tr>' . LF;
    }

   $tbl_elem .= '</table>';

    return $tbl_elem;
}

function build_hdn_elem($prm_content_arr) {
    $hdn_elem = NULL;

    foreach ($prm_content_arr as $key => $val) {
        $hdn_elem .= '<input type="hidden" name="' . $key . '" value="' . $val . '">' . LF;
    }

    return $hdn_elem;
}
?>
