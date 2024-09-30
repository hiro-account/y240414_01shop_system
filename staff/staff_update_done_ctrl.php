<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'CmnPdo.php';

const UPDATE_FAILED = TS_P . S_KOSHIN . S_SHIPPAI . S_SYSTEM_SHOGAI_HASSEI . TE_P;
const I_M2 = -2;

function get_content($prm_post) {
    $sql_part = NULL;

    foreach ($prm_post as $key => $value) {
        $val = NULL;

        switch ($key) {
            case ID:
            case PRIVILEGE:
                break;

            case SEX:
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
            $m_staff_stmt = $cmn_pdo->prepare(
                'UPDATE m_staff_for_dev SET '
                . substr($sql_part, I_0, I_M2)
                . ', updater_id = ' . $_SESSION[STAFF_ID]
                . ' WHERE id = ' . $prm_post[ID]
            );

            $m_staff_result = $m_staff_stmt->execute();

            if (!$m_staff_result) {
                return UPDATE_FAILED;
            }
        }

        if (isset($prm_post[PRIVILEGE])) {
            $t_privilege_stmt
                = $cmn_pdo->prepare(
                    'UPDATE t_privilege_for_dev SET privilege = ?, updater_id = ? WHERE id = ?'
                );

            $t_privilege_result
                = $t_privilege_stmt->execute(
                    array($prm_post[PRIVILEGE], $_SESSION[STAFF_ID], $prm_post[ID])
                );

            if (!$t_privilege_result) {
                return UPDATE_FAILED;
            }
        }
    } catch (Exception $e) {
        return UPDATE_FAILED;
    }

    return add_p(S_KOSHIN . S_KANRYO);
}
?>
