<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');

const CREATE_FAILED = '<p>登録失敗（システム障害発生）</p>';

function get_content($prm_post) {
    // mysqliのコンストラクタの例外用設定
    mysqli_report(MYSQLI_REPORT_STRICT);

    //TODO:トランザクションの実装
    try {
        $mysqli = new mysqli('localhost', 'root', '', 'y240608_01');

        if ($mysqli->connect_error) {
            return CREATE_FAILED;
        } else {
            $mysqli->set_charset('utf8');
        }

        // スタッフの登録前のスタッフマスタのidの最大値を読み出し
        $bf_staff_max_id = read_max_id($mysqli, 'm_staff_for_dev');

        $sql_for_m_staff = 'INSERT INTO m_staff_for_dev '
            . '(last_name, first_name, last_name_kana, first_name_kana, sex, birth_year, birth_month, birth_day, creator_id) '
            . 'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

        if ($stmt = $mysqli->prepare($sql_for_m_staff)) {
            $stmt->bind_param('ssssisssi',
                $prm_post['txt_last_name'],
                $prm_post['txt_first_name'],
                $prm_post['txt_last_name_kana'],
                $prm_post['txt_first_name_kana'],
                $prm_post['slct_sex'],
                $prm_post['slct_birth_year'],
                $prm_post['slct_birth_month'],
                $prm_post['slct_birth_day'],
                $_SESSION[STAFF_ID]);
            $stmt->execute();
            $stmt->close();
        }

        // スタッフの登録後のスタッフマスタのidの最大値を読み出し
        $af_staff_max_id = read_max_id($mysqli, 'm_staff_for_dev');

        // 登録後のidの最大値＝登録前のidの最大値＋1であるはず
        if ($af_staff_max_id !== $bf_staff_max_id + I_1) {
            // 登録後のidの最大値が正しくない場合
            $mysqli->close();

            throw new Exception('スタッフ登録後のidの最大値の確認エラー');
        }

        // 権限テーブルに登録後のidの最大値をidとして権限を登録する
        if ($stmt_for_t_privilege = $mysqli->prepare('INSERT INTO t_privilege_for_dev (id, privilege, creator_id) VALUES (?, ?, ?)')) {
//             $stmt_for_t_privilege->bind_param('isi', $af_staff_max_id, $_SESSION['privilege'], $_SESSION[STAFF_ID]);
            $stmt_for_t_privilege->bind_param('isi', $af_staff_max_id, $_POST['rdo_privilege'], $_SESSION[STAFF_ID]);
            $stmt_for_t_privilege->execute();
            $stmt_for_t_privilege->close();
        }

        // 権限の登録後の権限テーブルのidの最大値＝スタッフマスタのidの最大値であるはず
        if (read_max_id($mysqli, 't_privilege_for_dev') !== $af_staff_max_id) {
            // 登録後のidの最大値が正しくない場合
            $mysqli->close();

            throw new Exception('権限登録後のidの最大値の確認エラー');
        }

        // 仮パスワードを作る
        $pswd = 'password' . strval($af_staff_max_id);

        // パスワードテーブルに登録後のidの最大値をidとして仮パスワードを登録する
        if ($stmt_for_t_pswd = $mysqli->prepare('INSERT INTO t_password_for_dev (id, temporary, creator_id) VALUES (?, ?, ?)')) {
            $stmt_for_t_pswd->bind_param('isi', $af_staff_max_id, $pswd, $_SESSION[STAFF_ID]);
            $stmt_for_t_pswd->execute();
            $stmt_for_t_pswd->close();
        }

        // 仮パスワードの登録後のパスワードテーブルのidの最大値＝スタッフマスタのidの最大値であるはず
        if (read_max_id($mysqli, 't_password_for_dev') !== $af_staff_max_id) {
            // 登録後のidの最大値が正しくない場合
            $mysqli->close();

            throw new Exception('仮パスワード登録後のidの最大値の確認エラー');
        }

        // 削除ステータステーブルに登録後のidの最大値をidとして登録する
        if ($stmt_for_t_lgcl_del = $mysqli->prepare('INSERT INTO t_logical_delete_for_dev (id, creator_id) VALUES (?, ?)')) {
            $stmt_for_t_lgcl_del->bind_param('ii', $af_staff_max_id, $_SESSION[STAFF_ID]);
            $stmt_for_t_lgcl_del->execute();
            $stmt_for_t_lgcl_del->close();
        }

        // 登録後の削除ステータステーブルのidの最大値＝スタッフマスタのidの最大値であるはず
        if (read_max_id($mysqli, 't_logical_delete_for_dev') !== $af_staff_max_id) {
            // 登録後のidの最大値が正しくない場合
            $mysqli->close();

            throw new Exception('削除ステータス登録後のidの最大値の確認エラー');
        }

        $mysqli->close();
    } catch (Exception $e) {
//         return CREATE_FAILED;
        return $e;
    }

    return add_p('登録完了');
}

function read_max_id($prm_mysqli, $prm_tbl) {
    if ($result = $prm_mysqli->query('SELECT MAX(id) AS max_id FROM ' . $prm_tbl)) {
        $max_id = $result->fetch_assoc()['max_id'];
        $result->close();

        return intval($max_id);
    }

    return -1;
}



?>
