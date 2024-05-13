<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

function get_content_arr($prm_post) {
    //
    $posted_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 未入力、未選択の項目のチェック
    $empty_msg_arr = array_merge(check_unfilled_item_rtn_arr($item_txt_arr, $posted_arr),
        check_unselected_item_rtn_arr($item_slct_arr, $posted_arr));

    if (count($empty_msg_arr) > 0) {
        // 未入力、未選択の項目がある場合、エラーページに遷移する
        header($header . implode(DELIMITER, sort_msg_rtn_arr($item_key_arr, $empty_msg_arr)));
        exit();
    }



}


?>
