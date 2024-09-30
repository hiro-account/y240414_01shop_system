<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'CmnPdo.php';

const NEW_PSWD = 'txt_new_password_1';
const NEW_PSWD_CNFRM = 'txt_new_password_2';

const S_ATARASHII = '新しい';
const S_HENKO_SHIPPAI = TS_P . S_PASSWORD . S_HENKO . S_SHIPPAI . S_SYSTEM_SHOGAI_HASSEI . TE_P . LF;

function get_content($prm_post) {
    // 入力値のサニタイズと空白文字の除去
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    $item_key_nm_arr = array(NEW_PSWD => S_ATARASHII . S_PASSWORD
        , NEW_PSWD_CNFRM => S_ATARASHII . S_PASSWORD . S_KAKUNIN);

    // 未入力の項目のチェック
    $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array(STAFF_ID));

    if (isset($empty_msg)) {
        // 未入力の項目がある場合、エラーメッセージを表示する
        return $empty_msg . add_div(A_HISTORY_BACK) . LF;
    }

    $invalid_msg = NULL;

    // 新しいパスワードのチェック
    // 新しいパスワードの文字種のチェック
    //TODO:24年05月19日時点のパスワードの扱い：前後の半角スペースはチェック前に削除された状態
    $checked_alphanumeric_msg = chk_alphanumeric(array_slice($item_key_nm_arr, I_1, I_2), $item_val_arr);

    if (isset($checked_alphanumeric_msg)) {
        // 新しいパスワードの文字種に不備がある場合
        $invalid_msg .= $checked_alphanumeric_msg;
    } else if (strcmp($item_val_arr[NEW_PSWD], $item_val_arr[NEW_PSWD_CNFRM]) !== I_0) {
        // 新しいパスワードと新しいパスワード（確認）が一致しない場合
        $invalid_msg .= add_p(S_ATARASHII . S_PASSWORD . 'と' . S_ATARASHII . S_PASSWORD . S_KAKUNIN . 'が一致しない') . LF;
    }

    if (isset($invalid_msg)) {
        // 新しいパスワードに不備がある場合、エラーメッセージを表示する
        return $invalid_msg . add_div(A_HISTORY_BACK) . LF;
    }

    try {
        $cmn_pdo = new CmnPdo();
        $stmt = $cmn_pdo->prepare(
            'UPDATE t_password_for_dev
            SET current = ?, temporary = NULL, updater_id= ?  WHERE id = ?'
        );
        $result = $stmt->execute(array(
            password_hash($item_val_arr[NEW_PSWD], PASSWORD_DEFAULT)
            , $item_val_arr[STAFF_ID]
            , $item_val_arr[STAFF_ID]
        ));

        //TODO:例外発生時の処理とまとめることができないか
        if (!$result) {
            return S_HENKO_SHIPPAI . add_div('<a href="./staff_login.html">' . S_STAFF . S_LOGIN . 'へ</a>') . LF;
        }
    } catch (Exception $e) {
        return S_HENKO_SHIPPAI . add_div('<a href="./staff_login.html">' . S_STAFF . S_LOGIN . 'へ</a>') . LF;
    }

    return add_p(S_PASSWORD . 'を' . S_HENKO . 'した') . LF . '<div class="m-t-1em"><a href="./staff_login.html">' . S_STAFF . S_LOGIN . 'へ</a></div>' . LF;
}
?>
