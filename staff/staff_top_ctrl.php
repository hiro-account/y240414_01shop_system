<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
require_once $to_cmn . 'func.php';
require_once $to_cmn . 'CmnPdo.php';

//TODO:定数移動
const E_STAFF_CREATE = '<div><a href="staff_create.php">' . S_STAFF . S_TOROKU . '</a></div>' . LF;
const T_INPUT_HIDDEN = '<input type="hidden" name="';
const P_VALUE = '" value="';

const QUERY =<<< EOQ
SELECT m.id AS id , m.last_name AS last_name, m.first_name AS first_name
FROM m_staff_for_dev AS m INNER JOIN t_logical_delete_for_dev AS d ON m.id=d.id
WHERE d.flag=FALSE
ORDER BY m.id
EOQ;

const FOR_STAFF_TOP = <<<EOT
<div class="m-t-05em">スタッフ一覧（スタッフ更新、スタッフ削除はスタッフ詳細を表示）</div>
<form method="post" action="staff_detail.php">
<table class="border">
<tr><th>スタッフID</th><th>スタッフ名</th><th>スタッフ詳細</th><tr>

EOT;

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

            $last_staff_id = $mixed[ID];
            $staff_list .= '<tr><td>' . $last_staff_id . '</td><td>' . $mixed[LAST_NAME] . $mixed[FIRST_NAME] . '</td><td class="t-a-c"><input type="submit" name="staff_id_' . $last_staff_id . '" value="表示"></td></tr>' . LF;

            if (!isset($first_staff_id)) {
                $first_staff_id = $last_staff_id;
            }
        }
    } catch (Exception $e) {
        return TS_P . S_STAFF . '一覧' . S_YOMIDASHI . S_SHIPPAI . S_SYSTEM_SHOGAI_HASSEI . TE_P . LF;
    }

    if (!isset($staff_list)) {
        return E_STAFF_CREATE;
    }

    //TODO:staff_id→idへの更新検討
    return E_STAFF_CREATE . FOR_STAFF_TOP . $staff_list . '</table>' . LF
        . T_INPUT_HIDDEN . 'first_staff_id' . P_VALUE . $first_staff_id . '">' . LF
        . T_INPUT_HIDDEN . 'last_staff_id' . P_VALUE . $last_staff_id . '">' . LF
        . '</form>' . LF;
}
?>
