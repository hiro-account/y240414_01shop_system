<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'query.php';
// const QUERY =<<<EOQ
// UPDATE



// EOQ;

const QUERY = 'UPDATE m_staff_for_dev SET ';



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

        if (isset($val)) {
            $sql_part .= $key . ' = ' . $val . ', ';
        }

    }

    // var_dump(substr($sql_part, I_0, -3));

    $temp = 'UPDATE m_staff_for_dev SET '
            . substr($sql_part, I_0, -3)
            . ' updater_id = ' . $_SESSION[STAFF_ID]
            . ' WHERE id = ' . $prm_post['id'];

    var_dump($temp);

    try {
        $cmn_pdo = new CmnPdo();

        $m_staff_stmt = $cmn_pdo->prepare($temp);
        $m_staff_result = $m_staff_stmt->execute();

        if (!$m_staff_result) {
            return CREATE_FAILED;
        }





    } catch (Exception $e) {
        
    }




}

?>
