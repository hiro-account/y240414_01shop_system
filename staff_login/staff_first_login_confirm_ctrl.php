<?php
//TODO:requireの整理
require_once '../cmn/func.php';
require_once '../cmn/temp_const.php';

const NW = '新しい';

function get_content($prm_post) {
    // 入力値のサニタイズと空白文字の除去
    $item_val_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    $item_key_nm_arr = array(/*'txt_current_password' => '現在のパスワード'
        ,*/ 'txt_new_password_1' => '新しいパスワード'
        , 'txt_new_password_2' => '新しいパスワード（確認）');

    // 未入力の項目のチェック
    $empty_msg = check_unenter_unslct_item($item_key_nm_arr, $item_val_arr, array('staff_id'));

    if (isset($empty_msg)) {
        // 未入力の項目がある場合、エラーメッセージを表示する
        return $empty_msg;
    }

    $invalid_msg = NULL;

    // 新しいパスワードのチェック
    // 新しいパスワードの文字種のチェック
    //TODO:24年05月19日時点のパスワードの扱い：前後の半角スペースはチェック前に削除された状態
    $checked_alphanumeric_msg = chk_alphanumeric(array_slice($item_key_nm_arr, 1, I_2), $item_val_arr);

    if (isset($checked_alphanumeric_msg)) {
        // 新しいパスワードの文字種に不備がある場合
        $invalid_msg .= $checked_alphanumeric_msg;
    } else if (strcmp($item_val_arr['txt_new_password_1'], $item_val_arr['txt_new_password_2']) !== I_0) {
        // 新しいパスワードと新しいパスワード（確認）が一致しない場合
        $invalid_msg .= add_p(NW . PASSWORD . 'と' . NW . PASSWORD . CONFIRM . 'が一致しない') . LF;
    }

    if (isset($invalid_msg)) {
        // 新しいパスワードに不備がある場合、エラーメッセージを表示する
        return $invalid_msg;
    }

    // mysqliのコンストラクタの例外用設定
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $mysqli = new mysqli('localhost', 'root', '', 'y240608_01');

        if ($mysqli->connect_error) {
            return CREATE_FAILED;
        } else {
            $mysqli->set_charset('utf8');
        }

        $sql_for_m_staff = 'UPDATE t_password_for_dev SET current=?, temporary=NULL WHERE id=?';

                if ($stmt = $mysqli->prepare($sql_for_m_staff)) {
                    $new_password = password_hash($item_val_arr['txt_new_password_1'], PASSWORD_DEFAULT);
                    $stmt->bind_param('si', $new_password, $item_val_arr['staff_id']);
                    $stmt->execute();
                    $stmt->close();
                }

    } catch (Exception $e) {
    }

    return add_p(PASSWORD . 'を変更した') . LF;
}



?>
