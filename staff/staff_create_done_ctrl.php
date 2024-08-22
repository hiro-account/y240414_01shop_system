<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
// require_once $to_cmn . 'query.php';
require_once $to_cmn . 'CmnPdo.php';

const EX_ERR = -128;

const CREATE_FAILED = '<p>登録失敗（システム障害発生）</p>';

const M_STAFF = 'm_staff_for_dev';
const T_PRIVILEGE = 't_privilege_for_dev';

const QUERY_FOR_M_STAFF =<<<EOQ
INSERT INTO m_staff_for_dev (
  last_name
  , first_name
  , last_name_kana
  , first_name_kana
  , sex
  , birth_year
  , birth_month
  , birth_day
  , creator_id
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
EOQ;

function get_content($prm_post) {
    $post_arr = array(
        $prm_post['last_name']
        , $prm_post['first_name']
        , $prm_post['last_name_kana']
        , $prm_post['first_name_kana']
        , $prm_post['sex']
        , $prm_post['birth_year']
        , $prm_post['birth_month']
        , $prm_post['birth_day']
        , $_SESSION[STAFF_ID]
        // , $prm_post['privilege']
    );

    //TODO:トランザクションの実装。各idのチェック処理不要であれば削除
    try {
        $cmn_pdo = new CmnPdo();

        // スタッフの登録
        $m_staff_stmt = $cmn_pdo->prepare(QUERY_FOR_M_STAFF);
        $m_staff_result = $m_staff_stmt->execute($post_arr);

        if (!$m_staff_result) {
            return CREATE_FAILED;
        }

        // スタッフの登録後のスタッフマスタのidの最大値を読み出し
        $staff_max_id = read_max_id($cmn_pdo, M_STAFF);

        // 権限テーブルに登録後のidの最大値をidとして権限を登録する
        $t_privilege_stmt = $cmn_pdo->prepare('INSERT INTO ' . T_PRIVILEGE . ' (id, privilege, creator_id) VALUES (?, ?, ?)');
        $t_privilege_result = $t_privilege_stmt->execute(array($staff_max_id, $_POST['privilege'], $_SESSION[STAFF_ID]));

        if (!$t_privilege_result) {
            return CREATE_FAILED;
        }

        // 仮パスワードを作る
        $pswd = 'password' . strval($staff_max_id);

        // パスワードテーブルに登録後のidの最大値をidとして仮パスワードを登録する
        $t_password_stmt = $cmn_pdo->prepare('INSERT INTO t_password_for_dev (id, temporary, creator_id) VALUES (?, ?, ?)');
        $t_password_result = $t_password_stmt->execute(array($staff_max_id, $pswd, $_SESSION[STAFF_ID]));

        if (!$t_password_result) {
            return CREATE_FAILED;
        }

        // 削除ステータステーブルに登録後のidの最大値をidとして登録する
        $t_lgcl_del_stmt = $cmn_pdo->prepare('INSERT INTO t_logical_delete_for_dev (id, creator_id) VALUES (?, ?)');
        $t_lgcl_del_result = $t_lgcl_del_stmt->execute(array($staff_max_id, $_SESSION[STAFF_ID]));

        if (!$t_lgcl_del_result) {
            return CREATE_FAILED;
        }
    } catch (Exception $e) {
        return CREATE_FAILED;
    }

    return add_p('登録完了');
}

function read_max_id(CmnPdo $prm_cmn_pdo, $prm_tbl) {
    $stmt = $prm_cmn_pdo->prepare('SELECT MAX(id) AS max_id FROM ' . $prm_tbl);
    $stmt->execute();
    $mixed = $stmt->fetch(PDO::FETCH_ASSOC);

    return $mixed['max_id'];
}
?>
