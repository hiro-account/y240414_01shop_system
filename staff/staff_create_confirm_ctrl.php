<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'temp_const.php');
require_once($to_cmn . 'sortedFunc.php');
require_once './ItemWithErr.php';

//TODO:住所、電話番号、電子メールアドレスの追加
function get_content($prm_post)
{
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

    $item_key_nm_arr_ = array(
        'last_name' => new ItemWithErr('last_name', LAST_NAME, STR_EMPTY, NOT_ENTERED)                    // 0
        , 'first_name' => new ItemWithErr('first_name', FIRST_NAME, STR_EMPTY, NOT_ENTERED)               // 1
        , 'last_name_kana' => new ItemWithErr('last_name_kana', LAST_NAME . KANA, STR_EMPTY, NOT_ENTERED)           // 2
        , 'first_name_kana' => new ItemWithErr('first_name_kana', FIRST_NAME . KANA, STR_EMPTY, NOT_ENTERED)         // 3
        , 'sex' => new ItemWithErr('sex', SEX, NULL, NULL)                                   // 5
        , 'birth_year' => new ItemWithErr('birth_year', BIRTH_DATE . YEAR, '0000', UNSELECTED)          // 6
        , 'birth_month' => new ItemWithErr('birth_month', BIRTH_DATE . MONTH, '00', UNSELECTED)       // 7
        , 'birth_day' => new ItemWithErr('birth_day', BIRTH_DATE . DAY, '00', UNSELECTED)           // 8
        , 'privilege' => new ItemWithErr('privilege', '権限', NULL, UNSELECTED)                      // 10
    );






    $sex_arr = array('-', '男', '女');
    $privilege_arr = array('O' => '一般', 'A' => '管理者');
    //
    $separated_arr = separate_post_data($prm_post);

    $create_or_update = count($separated_arr['hidden']) > 0 ? '更新' : '登録';
    $html_h2 = "<h2>スタッフ{$create_or_update}</h2>";

    // 入力値のサニタイズと空白文字の除去
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($separated_arr['input']);

    // 入力値、選択値のチェック：ST ----------
    // 未入力、未選択の項目のチェック
    $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array('slct_sex', 'sex', 'birth_date'));

    if (isset($empty_msg)) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return $html_h2 . LF . $empty_msg;
    }

    //TODO:パスワードのチェックを削除し生年月日のチェックだけとなったため、エラーメッセージ表示の処理の更新を検討
    $invalid_msg = NULL;

    // 生年月日の妥当性のチェック
    if (!checkdate(intval($item_val_arr['slct_birth_month']), intval($item_val_arr['slct_birth_day']), intval($item_val_arr['slct_birth_year']))) {
        $invalid_msg .= add_p(BIRTH_DATE . 'が不正') . LF;
    }

    if (isset($invalid_msg)) {
        // 生年月日に不備がある場合、エラーメッセージを表示する
        return $html_h2 . LF . $invalid_msg;
    }

    //TODO:下記に限らず変数名整理
    $html_id = NULL;
    $form_action = NULL;

    if (count($separated_arr['hidden']) > 0) {
        // スタッフ更新からの遷移である場合
        //TODO:下記処理の概要記載
        $removed_arr = array();

        foreach ($separated_arr['input'] as $key => $value) {
            $pos = strpos($key, '_');

            if (!$pos) {
                $removed_arr[$key] = $value;
            } else {
                $removed_arr[substr($key, $pos + I_1)] = $value;
            }
        }

        $cnt = I_0;

        foreach ($separated_arr['hidden'] as $key => $value) {
            $pos = strpos($key, '_');
            $removed_value = $removed_arr[substr($key, $pos + I_1)];

            if (strcmp($removed_value, $value) === I_0) {
                $cnt++;
            }
        }

        if (count($separated_arr['hidden']) === $cnt) {
            return $html_h2 . LF . add_p('項目が一つも変更されていない') . LF;
        }

        $html_id = LF . '<tr><td>スタッフID</td><td>：' . $separated_arr['input']['id'] . '</td></tr>';
        $form_action = './staff_update_done.php';
    } else {
        // スタッフ登録からの遷移である場合
        //TODO:下記処理の概要記載
        $html_id = '';
        $form_action = './staff_create_done.php';
    }

    // 入力値、選択値のチェック：ED 画面表示項目、hidden項目の設定 ST ----------

    // 画面表示項目
    $cont_arr = array_slice($item_val_arr, I_0, 4);
    $cont_arr['sex'] = $sex_arr[intval($item_val_arr['slct_sex'])];
    $cont_arr['birth_date'] =
        $item_val_arr['slct_birth_year'] . '年'
        . process_month_and_day($item_val_arr['slct_birth_month']) . '月'
        . process_month_and_day($item_val_arr['slct_birth_day']) . '日';
    $cont_arr['rdo_privilege'] = $privilege_arr[$item_val_arr['rdo_privilege']];
    //     $cont_arr['password'] = '非表示';
    //     $tbl_elem = build_tbl_elem($item_key_nm_arr, $cont_arr, array('slct_sex', 'slct_birth_year','slct_birth_month','slct_birth_day'/*, 'txt_password_1', 'txt_password_2'*/));
    $tbl_elem = '<table>' . $html_id
        . build_tbl_elem($item_key_nm_arr, $cont_arr, array('slct_sex', 'slct_birth_year', 'slct_birth_month', 'slct_birth_day'))
        . '</table>';




    // hidden項目
    $hdn_elem = build_hdn_elem($item_val_arr);

    return <<<EOE
{$html_h2}
<p>下記の内容で問題なければ実行ボタンを押す</p>
{$tbl_elem}
<form method="post" action="{$form_action}">
{$hdn_elem}<p><input type="submit" value="実行"></p>
</form>

EOE;
}

// function build_err_content($prm_err_msg_arr) {
//     $content = NULL;

//     foreach ($prm_err_msg_arr as $value) {
//         $content .= add_p($value);
//     }

//     return $content;
// }

function separate_post_data($prm_post_data)
{
    $input_arr = array();
    $hidden_arr = array();

    foreach ($prm_post_data as $key => $value) {
        if (strpos($key, 'hidden_') === I_0) {
            $hidden_arr[$key] = $value;
        } else {
            $input_arr[$key] = $value;
        }
    }

    return array('input' => $input_arr, 'hidden' => $hidden_arr);
}





function build_tbl_elem($prm_item_key_name_arr, $prm_content_arr, $prm_continue_key_arr = NULL)
{
    //     $tbl_elem = '<table>' . LF;
    $tbl_elem = LF;

    foreach ($prm_item_key_name_arr as $key => $val) {
        if (isset($prm_continue_key_arr) && in_array($key, $prm_continue_key_arr)) {
            continue;
        }

        $tbl_elem .= '<tr><td>' . $val . '</td><td>：' . $prm_content_arr[$key] . '</td></tr>' . LF;
    }

    //    $tbl_elem .= '</table>';

    return $tbl_elem;
}

function build_hdn_elem($prm_content_arr)
{
    $hdn_elem = NULL;

    foreach ($prm_content_arr as $key => $val) {
        $hdn_elem .= '<input type="hidden" name="' . $key . '" value="' . $val . '">' . LF;
    }

    return $hdn_elem;
}
