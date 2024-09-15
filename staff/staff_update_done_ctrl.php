<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'query.php';
require_once $to_cmn . 'CmnPdo.php';

const UPDATE_FAILED = '<p>更新失敗（システム障害発生）</p>';
const I_M2 = -2;

function get_content($prm_post) {
    $sql_part = NULL;

    foreach ($prm_post as $key => $value) {
        $val = NULL;

        switch ($key) {
            case 'id':
            case 'privilege':
                break;

            case 'sex':
                $val = $value;
                break;

            default:
                $val = HF_S_QUOT . $value . HF_S_QUOT;
                break;
        }

        if (!is_null($val)) {
            $sql_part .= $key . ' = ' . $val . ', ';
        }
    }

    try {
        $cmn_pdo = new CmnPdo();

        if (!is_null($sql_part)) {
            $temp_q = 'UPDATE m_staff_for_dev SET '
                . substr($sql_part, I_0, I_M2)
                . ', updater_id = ' . $_SESSION[STAFF_ID]
                . ' WHERE id = ' . $prm_post['id'];
            $m_staff_stmt = $cmn_pdo->prepare(
                // 'UPDATE m_staff_for_dev SET '
                // . substr($sql_part, I_0, I_M2)
                // . ', updater_id = ' . $_SESSION[STAFF_ID]
                // . ' WHERE id = ' . $prm_post['id']
                $temp_q
            );

            $m_staff_result = $m_staff_stmt->execute();

            if (!$m_staff_result) {
                return UPDATE_FAILED;
            }
        }

        if (isset($prm_post['privilege'])) {
            $t_privilege_stmt
                = $cmn_pdo->prepare(
                    'UPDATE t_privilege_for_dev SET privilege = ?, updater_id = ? WHERE id = ?'
                );

            $t_privilege_result
                = $t_privilege_stmt->execute(
                    array($prm_post['privilege'], $_SESSION[STAFF_ID], $prm_post['id'])
                );

            if (!$t_privilege_result) {
                return UPDATE_FAILED;
            }
        }
    } catch (Exception $e) {
        return UPDATE_FAILED;
    }

    return add_p('更新完了');
}
?>
