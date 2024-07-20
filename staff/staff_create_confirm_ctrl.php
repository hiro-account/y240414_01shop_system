<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'temp_const.php');
require_once($to_cmn . 'sortedFunc.php');
require_once './Item.php';

//TODO:住所、電話番号、電子メールアドレスの追加
function get_content($prm_post)
{
    //TODO:配列の要素、配置位置要整理
    $item_key_nm_arr = array(
        'txt_last_name' => L_LAST_NAME                    // 0
        , 'txt_first_name' => L_FIRST_NAME                // 1
        , 'txt_last_name_kana' => L_LAST_NAME . L_KANA      // 2
        , 'txt_first_name_kana' => L_FIRST_NAME . L_KANA    // 3
        , 'slct_sex' => L_SEX                             // 4
        , 'sex' => L_SEX                                  // 5
        , 'slct_birth_year' => L_BIRTH_DATE . L_YEAR        // 6
        , 'slct_birth_month' => L_BIRTH_DATE . L_MONTH      // 7
        , 'slct_birth_day' => L_BIRTH_DATE . L_DAY          // 8
        , 'birth_date' => L_BIRTH_DATE                    // 9
        , 'rdo_privilege' => L_PRIVILEGE                     // 10
    );

    $from = isset($prm_post[N_ID]) ? FROM_UPDATE : FROM_CREATE;

    $item_arr = array(
        N_LAST_NAME => new Item(// 氏
            N_LAST_NAME,
            L_LAST_NAME,
            $prm_post[N_LAST_NAME],
            STR_EMPTY,
            NOT_ENTERED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_LAST_NAME] : NULL
        ), N_FIRST_NAME => new Item(// 名
            N_FIRST_NAME,
            L_FIRST_NAME,
            $prm_post[N_FIRST_NAME],
            STR_EMPTY,
            NOT_ENTERED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_FIRST_NAME] : NULL
        ), N_LAST_NAME_KANA => new Item(// 氏(カナ)
            N_LAST_NAME_KANA,
            L_LAST_NAME . L_KANA,
            $prm_post[N_LAST_NAME_KANA],
            STR_EMPTY,
            NOT_ENTERED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_LAST_NAME_KANA] : NULL
        ), N_FIRST_NAME_KANA => new Item(// 名(カナ)
            N_FIRST_NAME_KANA,
            L_FIRST_NAME . L_KANA,
            $prm_post[N_FIRST_NAME_KANA],
            STR_EMPTY,
            NOT_ENTERED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_FIRST_NAME_KANA] : NULL
        ), N_SEX => new Item(// 性別
            N_SEX,
            L_SEX,
            $prm_post[N_SEX],
            NULL,
            NULL,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_SEX] : NULL
        ), N_BIRTH_YEAR => new Item(// 生年月日の年
            N_BIRTH_YEAR,
            L_BIRTH_DATE . L_YEAR,
            $prm_post[N_BIRTH_YEAR],
            '0000',
            UNSELECTED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_BIRTH_YEAR] : NULL
        ), N_BIRTH_MONTH => new Item(// 生年月日の月
            N_BIRTH_MONTH,
            L_BIRTH_DATE . L_MONTH,
            $prm_post[N_BIRTH_MONTH],
            '00',
            UNSELECTED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_BIRTH_MONTH] : NULL
        ), N_BIRTH_DAY => new Item(// 生年月日の日
            N_BIRTH_DAY,
            L_BIRTH_DATE . L_DAY,
            $prm_post[N_BIRTH_DAY],
            '00',
            UNSELECTED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_BIRTH_DAY] : NULL
        ), N_PRIVILEGE => new Item(// 権限
            N_PRIVILEGE,
            L_PRIVILEGE,
            isset($prm_post[N_PRIVILEGE]) ? $prm_post[N_PRIVILEGE] : NULL,
            NULL,
            UNSELECTED,
            strcmp($from, FROM_UPDATE) === I_0 ? $prm_post[N_PREV . N_PRIVILEGE] : NULL
        )
    );

    $empty_msgs = NULL;
    $tmp_arr = array();

    foreach ($item_arr as $value) {
        $value->convert_sp_char_and_trim();
        $err_msg = $value->check_unenter_unslct_value();

        if (isset($err_msg)) {
            $empty_msgs .= add_p($err_msg) . LF;
        }

        $tmp_arr[] = $value->check_tmp_mtd();

    }

    var_dump($tmp_arr);


    $sex_arr = array('-', '男', '女');
    $privilege_arr = array('O' => '一般', 'A' => '管理者');
    //
    // $separated_arr = separate_post_data($prm_post);

    // $create_or_update = count($separated_arr['hidden']) > 0 ? '更新' : '登録';
    // $html_h2 = "<h2>スタッフ{$create_or_update}</h2>";
    $html_h2 = "<h2>スタッフ登録</h2>";

    // 入力値のサニタイズと空白文字の除去
    // $item_val_arr = convert_sp_char_and_trim_rtn_arr($separated_arr['input']);

    // 入力値、選択値のチェック：ST ----------
    // 未入力、未選択の項目のチェック
    // $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array('slct_sex', 'sex', 'birth_date'));

    if (isset($empty_msgs)) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return $html_h2 . LF . $empty_msgs;
    }

    //TODO:パスワードのチェックを削除し生年月日のチェックだけとなったため、エラーメッセージ表示の処理の更新を検討
    $invalid_msg = NULL;

    // 生年月日の妥当性のチェック
    if (!checkdate(
        intval($item_arr[N_BIRTH_MONTH]->get_verified_value()),
        intval($item_arr[N_BIRTH_DAY]->get_verified_value()),
        intval($item_arr[N_BIRTH_YEAR]->get_verified_value())
    )) {
        $invalid_msg .= add_p(L_BIRTH_DATE . 'が不正') . LF;
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
