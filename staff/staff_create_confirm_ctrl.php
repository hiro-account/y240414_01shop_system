<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once 'Item.php';

const S_00 = '00';

//TODO:一カ所からしか参照されていないため、直書き化検討(09/23)
const CREATE = 'create';
const UPDATE = 'update';

//TODO:住所、電話番号、電子メールアドレスの追加
function get_content($prm_post)
{
    // 遷移元
    $is_from_update = FALSE;
    $h2_content = NULL;
    $table_elem = NULL;
    $form_action = NULL;
    $hidden_id = NULL;

    if (strcmp($prm_post[FROM], UPDATE) === I_0) {
        $is_from_update = TRUE;
        $h2_content = S_KOSHIN;
        $table_elem = '<tr><td>' . S_STAFF . U_ID . '</td><td>：' . $prm_post[ID] . '</td></tr>' . LF;
        $form_action = './staff_update_done.php';
        $hidden_id = build_input_type_hidden(ID, $prm_post[ID]) . LF;
    } else if (strcmp($prm_post[FROM], CREATE) === I_0) {
        $h2_content = S_TOROKU;
        $form_action = './staff_create_done.php';
        $hidden_id = EMPTY_STR;
    }

    $h2 = '<h2>' . S_STAFF . $h2_content . '</h2>';

    //TODO:登録の場合のエラー値と前回の値について。strcmpでのnull警告回避のためそれにかえて空文字を設定したが問題ないか確認(24/08/22)
    $item_arr = array(
        LAST_NAME => new Item(// 氏
            LAST_NAME,
            S_SHI,
            $prm_post[LAST_NAME],
            EMPTY_STR,
            S_GA . S_MINYURYOKU,
            $is_from_update ? $prm_post[PREV . LAST_NAME] : EMPTY_STR
        ), FIRST_NAME => new Item(// 名
            FIRST_NAME,
            S_MEI,
            $prm_post[FIRST_NAME],
            EMPTY_STR,
            S_GA . S_MINYURYOKU,
            $is_from_update ? $prm_post[PREV . FIRST_NAME] : EMPTY_STR
        ), LAST_NAME . KANA => new Item(// 氏(カナ)
            LAST_NAME . KANA,
            S_SHI . S_KANA,
            $prm_post[LAST_NAME . KANA],
            EMPTY_STR,
            S_GA . S_MINYURYOKU,
            $is_from_update ? $prm_post[PREV . LAST_NAME . KANA] : EMPTY_STR
        ), FIRST_NAME . KANA => new Item(// 名(カナ)
            FIRST_NAME . KANA,
            S_MEI . S_KANA,
            $prm_post[FIRST_NAME . KANA],
            EMPTY_STR,
            S_GA . S_MINYURYOKU,
            $is_from_update ? $prm_post[PREV . FIRST_NAME . KANA] : EMPTY_STR
        ), SEX => new Item(// 性別
            SEX,
            S_SEIBETSU,
            $prm_post[SEX],
            EMPTY_STR,
            NULL,
            $is_from_update ? $prm_post[PREV . SEX] : EMPTY_STR
        ), BIRTH_YEAR => new Item(// 生年月日の年
            BIRTH_YEAR,
            S_SEINENGAPPI . S_NO . S_NEN,
            $prm_post[BIRTH_YEAR],
            '0000',
            S_GA . S_MISENTAKU,
            $is_from_update ? $prm_post[PREV . BIRTH_YEAR] : EMPTY_STR
        ), BIRTH_MONTH => new Item(// 生年月日の月
            BIRTH_MONTH,
            S_SEINENGAPPI . S_NO . S_TSUKI,
            $prm_post[BIRTH_MONTH],
            S_00,
            S_GA . S_MISENTAKU,
            $is_from_update ? $prm_post[PREV . BIRTH_MONTH] : EMPTY_STR
        ), BIRTH_DAY => new Item(// 生年月日の日
            BIRTH_DAY,
            S_SEINENGAPPI . S_NO . S_HI,
            $prm_post[BIRTH_DAY],
            S_00,
            S_GA . S_MISENTAKU,
            $is_from_update ? $prm_post[PREV . BIRTH_DAY] : EMPTY_STR
        ), PRIVILEGE => new Item(// 権限
            PRIVILEGE,
            S_KENGEN,
            isset($prm_post[PRIVILEGE]) ? $prm_post[PRIVILEGE] : NULL,
            EMPTY_STR,
            S_GA . S_MISENTAKU,
            $is_from_update ? $prm_post[PREV . PRIVILEGE] : EMPTY_STR
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

            continue;
        }

        if ($value->is_value_changed()) {
            $hidden_elems .= build_input_type_hidden($value->get_name(), $value->get_verified_value()) . LF;
        }

        $fmted_value = NULL;
        $verified_value = $value->get_verified_value();

        switch ($value->get_name()) {
            case SEX:
                $fmted_value = S_SEIBETSU_ARR[$verified_value];
                $value->set_formated_value(S_SEIBETSU_ARR[$verified_value]);

                break;

            case BIRTH_YEAR:
                $fmted_ymd = $verified_value . S_NEN;

                break;

            case BIRTH_MONTH:
                $fmted_ymd .= zero_to_empty($verified_value) . S_TSUKI;

                break;

            case BIRTH_DAY:
                $fmted_ymd .= zero_to_empty($verified_value) . S_HI;

                break;

            case PRIVILEGE:
                if (isset(S_KENGEN_ARR[$verified_value])) {
                    $fmted_privilege = S_KENGEN_ARR[$verified_value];
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
        intval($item_arr[BIRTH_MONTH]->get_verified_value()),
        intval($item_arr[BIRTH_DAY]->get_verified_value()),
        intval($item_arr[BIRTH_YEAR]->get_verified_value())
    )) {
        return $h2 . LF . add_p(S_SEINENGAPPI . S_GA . S_FUSEI) . LF;
    }

    if ($is_from_update && !isset($hidden_elems)) {
         // スタッフ更新からの遷移であり、かつ、項目が一つも変更されていない場合
         return $h2 . LF . add_p('項目が一つも' . S_HENKO . 'されていない') . LF;
    }

    // 入力値、選択値のチェック：ED 画面表示項目、hidden項目の設定 ST ----------

    $table_elem .= build_tr_td_label_value(S_SEINENGAPPI, $fmted_ymd) . LF;
    $table_elem .= build_tr_td_label_value($item_arr[PRIVILEGE]->get_label(), $fmted_privilege) . LF;

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

/**
 * 引数の先頭の'0'を削除して返す
 */
function zero_to_empty($prm_str_num) {
    return preg_replace('/^0+/', EMPTY_STR, $prm_str_num);
}

/**
 * hidden要素を組み立てて返す
 */
function build_input_type_hidden($prm_name, $prm_value) {
    return '<input type="hidden" name="' . $prm_name . '" value="' . $prm_value . '">';
}

/**
 * tableの項目を組み立てて返す
 */
function build_tr_td_label_value($prm_label, $prm_value) {
    return '<tr><td>' . $prm_label . '</td><td>：' . $prm_value . '</td></tr>';
}

?>
