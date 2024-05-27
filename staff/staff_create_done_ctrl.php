<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

function get_content($prm_post) {
    $input_parameter_arr = array(
        $prm_post['txt_last_name'],
        $prm_post['txt_first_name'],
        $prm_post['txt_last_name_kana'],
        $prm_post['txt_first_name_kana'],
        $prm_post['slct_sex'],
        $prm_post['slct_birth_year'] . '-' . sprintf('%02d', $prm_post['slct_birth_month']) . '-' . sprintf('%02d', $prm_post['slct_birth_day']),
        $prm_post['password']
    );

    try {
        execute_sql_rtn_PDOStatement('INSERT INTO mst_staff '
            . '(last_name, first_name, last_name_kana, first_name_kana, sex, birthday, password) '
            . 'VALUES (?, ?, ?, ?, ?, ?, ?)',
            $input_parameter_arr);
    } catch (Exception $e) {
        return add_p('登録失敗（システム障害発生）');
    }

    return add_p('登録完了');
}
?>
