<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

function get_content_arr($prm_post) {
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

    //
    $posted_arr = convert_sp_char_and_trim_rtn_arr($prm_post);

    // 未入力、未選択の項目のチェック
    $empty_msg_arr = array_merge(check_unfilled_item_rtn_arr($item_txt_arr, $posted_arr),
        check_unselected_item_rtn_arr($item_slct_arr, $posted_arr));

    if (count($empty_msg_arr) > 0) {
        // 未入力、未選択の項目がある場合、エラーページに遷移する
//         var_dump(sort_msg_rtn_arr($item_key_arr, $empty_msg_arr));


        $content_arr = NULL;
        //     $err_msg_arr = explode(DELIMITER, $_GET['err_msg']);


        $err_msg_arr = sort_msg_rtn_arr($item_key_arr, $empty_msg_arr);

        for ($i = 0; $i < count($err_msg_arr); $i++) {
            $content_arr .= add_p($err_msg_arr[$i]) . LF;
        }

        return $content_arr;





    }



}


?>
