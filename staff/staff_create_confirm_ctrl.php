<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'temp_const.php');
require_once($to_cmn . 'sortedFunc.php');
require_once './Item.php';

// 性別
define('SEXES', ['0' => '-', '1' => '男', '2' => '女']);
// 権限
define('PRIVILEGES', ['O' => '一般', 'A' => '管理者']);

//TODO:住所、電話番号、電子メールアドレスの追加
function get_content($prm_post)
{
    // 遷移元
    // $is_from_create = FALSE;
    $is_from_update = FALSE;
    $h2_content = NULL;
    $table_elem = NULL;
    $form_action = NULL;
    $hidden_id = NULL;

    if (strcmp($prm_post[FROM], UPDATE) === I_0) {
        $is_from_update = TRUE;
        $h2_content = '更新';
        $table_elem = '<tr><td>スタッフID</td><td>：' . $prm_post['id'] . '</td></tr>' . LF;
        $form_action = './staff_update_done.php';
        $hidden_id = '<input type="hidden" name="id" value="'  . $prm_post['id'] . '">' . LF;
    } else if (strcmp($prm_post[FROM], CREATE) === I_0) {
        // $is_from_create = TRUE;
        $h2_content = '登録';
        $form_action = './staff_create_done.php';
        $hidden_id = STR_EMPTY;
    }

    $h2 = "<h2>スタッフ{$h2_content}</h2>";

    $item_arr = array(
        N_LAST_NAME => new Item(// 氏
            N_LAST_NAME,
            L_LAST_NAME,
            $prm_post[N_LAST_NAME],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_LAST_NAME] : NULL,
            TRUE
        ), N_FIRST_NAME => new Item(// 名
            N_FIRST_NAME,
            L_FIRST_NAME,
            $prm_post[N_FIRST_NAME],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_FIRST_NAME] : NULL,
            TRUE
        ), N_LAST_NAME_KANA => new Item(// 氏(カナ)
            N_LAST_NAME_KANA,
            L_LAST_NAME . L_KANA,
            $prm_post[N_LAST_NAME_KANA],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_LAST_NAME_KANA] : NULL,
            TRUE
        ), N_FIRST_NAME_KANA => new Item(// 名(カナ)
            N_FIRST_NAME_KANA,
            L_FIRST_NAME . L_KANA,
            $prm_post[N_FIRST_NAME_KANA],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_FIRST_NAME_KANA] : NULL,
            TRUE
        ), N_SEX => new Item(// 性別
            N_SEX,
            L_SEX,
            $prm_post[N_SEX],
            NULL,
            NULL,
            $is_from_update ? $prm_post[N_PREV . N_SEX] : NULL,
            FALSE
        ), N_BIRTH_YEAR => new Item(// 生年月日の年
            N_BIRTH_YEAR,
            L_BIRTH_DATE . L_YEAR,
            $prm_post[N_BIRTH_YEAR],
            '0000',
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_BIRTH_YEAR] : NULL,
            FALSE
        ), N_BIRTH_MONTH => new Item(// 生年月日の月
            N_BIRTH_MONTH,
            L_BIRTH_DATE . L_MONTH,
            $prm_post[N_BIRTH_MONTH],
            '00',
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_BIRTH_MONTH] : NULL,
            FALSE
        ), N_BIRTH_DAY => new Item(// 生年月日の日
            N_BIRTH_DAY,
            L_BIRTH_DATE . L_DAY,
            $prm_post[N_BIRTH_DAY],
            '00',
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_BIRTH_DAY] : NULL,
            FALSE
        ), N_PRIVILEGE => new Item(// 権限
            N_PRIVILEGE,
            L_PRIVILEGE,
            isset($prm_post[N_PRIVILEGE]) ? $prm_post[N_PRIVILEGE] : NULL,
            NULL,
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_PRIVILEGE] : NULL,
            FALSE
        )
    );

    $empty_msgs = NULL;
    $hidden_elems = NULL;
    $fmted_ymd = NULL;
    $fmted_privilege = NULL;

    foreach ($item_arr as $value) {
        // 値のサニタイズと前後スペースの除去
        $value->convert_sp_char_and_trim();
        // 未入力、未選択の項目の確認
        $err_msg = $value->check_unenter_unslct_value();

        if (isset($err_msg)) {
            $empty_msgs .= add_p($err_msg) . LF;
        }

        if ($value->is_value_changed()) {
            $hidden_elems .= build_input_type_hidden($value->get_name(), $value->get_verified_value()) . LF;
        }

        $fmted_value = NULL;
        $verified_value = $value->get_verified_value();

        switch ($value->get_name()) {
            case N_SEX:
                $fmted_value = SEXES[$verified_value];
                $value->set_formated_value(SEXES[$verified_value]);

                break;

            case N_BIRTH_YEAR:
                $fmted_ymd = $verified_value . '年';

                break;

            case N_BIRTH_MONTH:
                $fmted_ymd .= zero_to_empty($verified_value) . '月';

                break;

            case N_BIRTH_DAY:
                $fmted_ymd .= zero_to_empty($verified_value) . '日';

                break;

            case N_PRIVILEGE:
                if (isset(PRIVILEGES[$verified_value])) {
                    $fmted_privilege = PRIVILEGES[$verified_value];
                }

                break;

            default:
                $fmted_value = $verified_value;

                break;
        }

        if (isset($fmted_value)) {
            $table_elem .= build_tr_td_label_value($value->get_label(), $fmted_value) . LF;
        }
    }

    if (isset($empty_msgs)) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return $h2 . LF . $empty_msgs;
    }

    // 生年月日の妥当性のチェック
    if (!checkdate(
        intval($item_arr[N_BIRTH_MONTH]->get_verified_value()),
        intval($item_arr[N_BIRTH_DAY]->get_verified_value()),
        intval($item_arr[N_BIRTH_YEAR]->get_verified_value())
    )) {
        return $h2 . LF . add_p(L_BIRTH_DATE . 'が不正') . LF;
    }

    if ($is_from_update && !isset($hidden_elems)) {
         // スタッフ更新からの遷移であり、かつ、項目が一つも変更されていない場合
         return $h2 . LF . add_p('項目が一つも変更されていない') . LF;
    }

    // 入力値、選択値のチェック：ED 画面表示項目、hidden項目の設定 ST ----------

    $table_elem .= build_tr_td_label_value(L_BIRTH_DATE, $fmted_ymd) . LF;
    $table_elem .= build_tr_td_label_value($item_arr[N_PRIVILEGE]->get_label(), $fmted_privilege) . LF;

    return <<<EOE
{$h2}
<p>下記の内容で問題なければ実行ボタンを押す</p>
<table>
{$table_elem}</table>
<form method="post" action="{$form_action}">
{$hidden_id}{$hidden_elems}<p><input type="submit" value="実行"></p>
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
    //     $table_elem = '<table>' . LF;
    $table_elem = LF;

    foreach ($prm_item_key_name_arr as $key => $val) {
        if (isset($prm_continue_key_arr) && in_array($key, $prm_continue_key_arr)) {
            continue;
        }

        $table_elem .= '<tr><td>' . $val . '</td><td>：' . $prm_content_arr[$key] . '</td></tr>' . LF;
    }

    //    $table_elem .= '</table>';

    return $table_elem;
}

function build_hdn_elem($prm_content_arr)
{
    $hidden_elems = NULL;

    foreach ($prm_content_arr as $key => $val) {
        $hidden_elems .= '<input type="hidden" name="' . $key . '" value="' . $val . '">' . LF;
    }

    return $hidden_elems;
}

function build_input_type_hidden($prm_name, $prm_value) {
    return '<input type="hidden" name="' . $prm_name . '" value="' . $prm_value . '">';
}


function build_tr_td_label_value($prm_label, $prm_value) {
    return '<tr><td>' . $prm_label . '</td><td>：' . $prm_value . '</td></tr>';
}

function build_table_elem(Item $prm_item) {
    // $elem_0 = NULL;
    // $elem_1 = NULL;
    // $elem_2 = NULL;
    $tmp_arr = array(NULL, NULL, NULL);

    $value = $prm_item->get_verified_value();

    switch ($prm_item->get_name()) {
        case N_SEX:
            $tmp_arr[I_2]  = NULL;
            //TODO:定数作る
            break;

        case N_BIRTH_YEAR:
            // $y_m_d_arr[N_BIRTH_YEAR] = $value;
            $tmp_arr[I_0] = $value . '年';

            break;

        case N_BIRTH_MONTH:
            // $y_m_d_arr[N_BIRTH_MONTH] = $value;
            $tmp_arr[I_0] = $value . '月';
                    //TODO:9以下の場合
            break;

        case N_BIRTH_DAY:
            // $y_m_d_arr[N_BIRTH_DAY] = $value;
            $tmp_arr[I_0] = $value . '日';

            break;

        case N_PRIVILEGE:
            $tmp_arr[I_1] = NULL;

            break;

        default:
        $tmp_arr[I_2] = $value;

            break;
    }

    return $tmp_arr;
}

function build_table_element(Item $prm_item) {
    $tmp_arr = array(NULL, NULL, NULL);

    $value = $prm_item->get_verified_value();

    switch ($prm_item->get_name()) {
        case N_SEX:
            $tmp_arr[I_2]  = NULL;
            //TODO:定数作る
            break;

        case N_BIRTH_YEAR:
            // $y_m_d_arr[N_BIRTH_YEAR] = $value;
            $tmp_arr[I_0] = $value . '年';

            break;

        case N_BIRTH_MONTH:
            // $y_m_d_arr[N_BIRTH_MONTH] = $value;
            $tmp_arr[I_0] = $value . '月';
                    //TODO:9以下の場合
            break;

        case N_BIRTH_DAY:
            // $y_m_d_arr[N_BIRTH_DAY] = $value;
            $tmp_arr[I_0] = $value . '日';

            break;

        case N_PRIVILEGE:
            $tmp_arr[I_1] = NULL;

            break;

        default:
        $tmp_arr[I_2] = $value;

            break;
    }

    return $tmp_arr;
}

function zero_to_empty($prm_temp) {
    return preg_replace('/^0+/', '', $prm_temp);
}



