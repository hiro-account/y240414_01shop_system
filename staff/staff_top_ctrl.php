<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once $to_cmn . 'CmnPdo.php';

//TODO:定数移動
const READ_FAILED = '<p>スタッフ一覧読み出し失敗（システム障害発生）</p>';
const A_STAFF_CREATE = '<div><a href="staff_create.php">スタッフ登録</a></div>' . LF;
const INPUT_HIDDEN = '<input type="hidden" name="';
const VALUE = '" value="';

const QUERY =<<< EOQ
SELECT m.id AS id , m.last_name AS last_name, m.first_name AS first_name
FROM m_staff_for_dev AS m INNER JOIN t_logical_delete_for_dev AS d ON m.id=d.id
WHERE d.flag=FALSE
ORDER BY m.id
EOQ;

function get_content() {
    $staff_list = NULL;
    $first_staff_id = NULL;
    $last_staff_id = NULL;

    try {
        $cmn_pdo = new CmnPdo();
        $stmt = $cmn_pdo->prepare(QUERY);
        $stmt->execute();

        while (TRUE) {
            $mixed = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($mixed == FALSE) {
                break;
            }

            $last_staff_id = $mixed['id'];
            $staff_list .= '<tr><td>' . $last_staff_id . '</td><td>' . $mixed['last_name'] . $mixed['first_name'] . '</td><td class="t-a-c"><input type="submit" name="staff_id_' . $last_staff_id . '" value="表示"></td></tr>' . LF;

            if (!isset($first_staff_id)) {
                $first_staff_id = $last_staff_id;
            }
        }
    } catch (Exception $e) {
        return READ_FAILED . LF;
    }

    if (!isset($staff_list)) {
        return A_STAFF_CREATE;
    }

    return A_STAFF_CREATE . FOR_STAFF_TOP . $staff_list . '</table>' . LF
        . INPUT_HIDDEN . 'first_staff_id' . VALUE . $first_staff_id . '">' . LF
        . INPUT_HIDDEN . 'last_staff_id' . VALUE . $last_staff_id . '">' . LF
        . '</form>' . LF;
}
?>
