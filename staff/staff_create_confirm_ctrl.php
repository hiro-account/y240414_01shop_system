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

    $sex_arr = array('-', '男', '女');
    $privilege_arr = array('O' => '一般', 'A' => '管理者');

    // 遷移元
    $is_from_create = FALSE;
    $is_from_update = FALSE;

    $create_or_update = NULL;

    if (strcmp($prm_post[FROM], UPDATE) === I_0) {
        $is_from_update = TRUE;
        $create_or_update = '更新';
    } else if (strcmp($prm_post[FROM], CREATE) === I_0) {
        $is_from_create = TRUE;
        $create_or_update = '登録';
    }

    $html_h2 = "<h2>スタッフ{$create_or_update}</h2>";

    $item_arr = array(
        N_LAST_NAME => new Item(// 氏
            N_LAST_NAME,
            L_LAST_NAME,
            $prm_post[N_LAST_NAME],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_LAST_NAME] : NULL,
            FALSE
        ), N_FIRST_NAME => new Item(// 名
            N_FIRST_NAME,
            L_FIRST_NAME,
            $prm_post[N_FIRST_NAME],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_FIRST_NAME] : NULL,
            FALSE
        ), N_LAST_NAME_KANA => new Item(// 氏(カナ)
            N_LAST_NAME_KANA,
            L_LAST_NAME . L_KANA,
            $prm_post[N_LAST_NAME_KANA],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_LAST_NAME_KANA] : NULL,
            FALSE
        ), N_FIRST_NAME_KANA => new Item(// 名(カナ)
            N_FIRST_NAME_KANA,
            L_FIRST_NAME . L_KANA,
            $prm_post[N_FIRST_NAME_KANA],
            STR_EMPTY,
            NOT_ENTERED,
            $is_from_update ? $prm_post[N_PREV . N_FIRST_NAME_KANA] : NULL,
            FALSE
        ), N_SEX => new Item(// 性別
            N_SEX,
            L_SEX,
            $prm_post[N_SEX],
            NULL,
            NULL,
            $is_from_update ? $prm_post[N_PREV . N_SEX] : NULL,
            TRUE
        ), N_BIRTH_YEAR => new Item(// 生年月日の年
            N_BIRTH_YEAR,
            L_BIRTH_DATE . L_YEAR,
            $prm_post[N_BIRTH_YEAR],
            '0000',
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_BIRTH_YEAR] : NULL,
            TRUE
        ), N_BIRTH_MONTH => new Item(// 生年月日の月
            N_BIRTH_MONTH,
            L_BIRTH_DATE . L_MONTH,
            $prm_post[N_BIRTH_MONTH],
            '00',
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_BIRTH_MONTH] : NULL,
            TRUE
        ), N_BIRTH_DAY => new Item(// 生年月日の日
            N_BIRTH_DAY,
            L_BIRTH_DATE . L_DAY,
            $prm_post[N_BIRTH_DAY],
            '00',
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_BIRTH_DAY] : NULL,
            TRUE
        ), N_PRIVILEGE => new Item(// 権限
            N_PRIVILEGE,
            L_PRIVILEGE,
            isset($prm_post[N_PRIVILEGE]) ? $prm_post[N_PRIVILEGE] : NULL,
            NULL,
            UNSELECTED,
            $is_from_update ? $prm_post[N_PREV . N_PRIVILEGE] : NULL,
            TRUE
        )
    );

    $empty_msgs = NULL;
    $changed_item_arr = array();
    $input_hidden = NULL;
    $table_ = NULL;
    $ymd_arr = array();
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

        // 未変更の項目の確認(スタッフ更新の場合)
        // $is_value_changed = $value->check_value_changed();

        // if (isset($is_value_changed)) {
        //     $changed_item_arr[$value->get_name()] = $value;
        // }

        //TODO:24/07/26性別、生年月日のフォーマットから

        if ($value->is_value_changed()) {
            // $input_hidden .= '<input type="hidden" name="' . $value->get_name() . '" value="' . $value->get_verified_value() . '">' . LF;
            $input_hidden .= build_input_type_hidden($value->get_name(), $value->get_verified_value()) . LF;
        }

        // $tmp_a = NULL;
        // $verified_value = $value->get_verified_value();

        // switch ($value->get_label()) {
        //     case N_SEX:
        //         $tmp_a = $sex_arr[intval($verified_value)];

        //         break;
            
        //     case N_BIRTH_YEAR:
        //         $y_m_d_arr[N_BIRTH_YEAR] = $verified_value;

        //         break;

        //     case N_BIRTH_MONTH:
        //         $y_m_d_arr[N_BIRTH_MONTH] = $verified_value;
    
        //         break;

        //     case N_BIRTH_DAY:
        //         $y_m_d_arr[N_BIRTH_DAY] = $verified_value;
        
        //         break;
        
        //     case N_PRIVILEGE:
        //         # code...
        //         break;
                
     
                

        //     default:
        //         $tmp_a = $verified_value;

        //         break;
        // }

        list($ymd, $privilege, $other) = build_table_elem($value);
        
        if (isset($ymd)) {
            $fmted_ymd .= $ymd;
        } 


        switch ($value->get_name()) {
            case N_SEX:
                $tmp_arr[I_2]  = NULL;
                //TODO:定数作る
                break;
            
            case N_BIRTH_YEAR:
                $ymd_arr[N_BIRTH_YEAR] = $value . '年';
                    
                break;
        
            case N_BIRTH_MONTH:
                $ymd_arr[N_BIRTH_MONTH] = $value . '月';
                        //TODO:9以下の場合
                break;
            
            case N_BIRTH_DAY:
                $ymd_arr[N_BIRTH_MONTH] = $value . '日';
                            
                break;
            
            case N_PRIVILEGE:
                $tmp_arr[I_1] = NULL;
    
                break;
    
            default:
            $tmp_arr[I_2] = $value;
                
                break;
        }
    








        $table_ .= build_tr_td_label_value($value->get_label(), $value->get_verified_value()) . LF;

    }

    if (isset($empty_msgs)) {
        // 未入力、未選択の項目がある場合、エラーメッセージを表示する
        return $html_h2 . LF . $empty_msgs;
    }

    // 生年月日の妥当性のチェック
    $invalid_birthday_msg = NULL;

    if (!checkdate(
        intval($item_arr[N_BIRTH_MONTH]->get_verified_value()),
        intval($item_arr[N_BIRTH_DAY]->get_verified_value()),
        intval($item_arr[N_BIRTH_YEAR]->get_verified_value())
    )) {
        $invalid_birthday_msg .= add_p(L_BIRTH_DATE . 'が不正') . LF;
    }

    if (isset($invalid_birthday_msg)) {
        // 生年月日に不備がある場合、エラーメッセージを表示する
        return $html_h2 . LF . $invalid_birthday_msg;
    }

    $tr_id = NULL;
    $form_action = NULL;

    if ($is_from_update) {
         // スタッフ更新からの遷移である場合
        //TODO:下記処理の概要記載
        if (!isset($input_hidden)) {
            // 項目が一つも変更されていない場合
            return $html_h2 . LF . add_p('項目が一つも変更されていない') . LF;
        }

        $tr_id = LF . '<tr><td>スタッフID</td><td>：' . $prm_post['id'] . '</td></tr>';
        $form_action = './staff_update_done.php';
    } else if ($is_from_create) {
        // スタッフ登録からの遷移である場合
        //TODO:下記処理の概要記載
        $tr_id = '';
        $form_action = './staff_create_done.php';
    }






    // 入力値、選択値のチェック：ED 画面表示項目、hidden項目の設定 ST ----------

    // 画面表示項目
    // $sex_arr = array('-', '男', '女');
    // $privilege_arr = array('O' => '一般', 'A' => '管理者');

    $cont_arr = array_slice($item_val_arr, I_0, 4);
    $cont_arr['sex'] = $sex_arr[intval($item_val_arr['slct_sex'])];
    $cont_arr['birth_date'] =
        $item_val_arr['slct_birth_year'] . '年'
        . process_month_and_day($item_val_arr['slct_birth_month']) . '月'
        . process_month_and_day($item_val_arr['slct_birth_day']) . '日';
    $cont_arr['rdo_privilege'] = $privilege_arr[$item_val_arr['rdo_privilege']];
    //     $cont_arr['password'] = '非表示';
    //     $tbl_elem = build_tbl_elem($item_key_nm_arr, $cont_arr, array('slct_sex', 'slct_birth_year','slct_birth_month','slct_birth_day'/*, 'txt_password_1', 'txt_password_2'*/));
    $tbl_elem = '<table>' . $tr_id
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
