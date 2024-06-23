<?php
$to_cmn = dirname(__FILE__) . '/../cmn/';
// require_once($to_cmn . 'const.php');
require_once($to_cmn . 'func.php');
require_once($to_cmn . 'sortedFunc.php');

const READ_FAILED = '<p>スタッフ詳細読み出し失敗（システム障害発生）</p>';



function get_content($prm_post) {
    $staff_id = NULL;

    for ($i = intval($prm_post['first_staff_id']); $i <= intval($prm_post['last_staff_id']); $i++) {
        if (isset($prm_post['staff_id_' . $i])) {
            $staff_id = $i;

            break;
        }
    }

    $column_arr = array('スタッフID' => 'id',
        '氏' => 'last_name',
        '名' => 'first_name',
        '氏（カナ）' => 'last_name_kana',
        '名（カナ）' => 'first_name_kana',
        '性別' => 'sex',
        '生年月日' => 'birthday',
        '登録日時' => 'created_date',
        '更新日時' => 'updated_date');

//     $mixed = NULL;

//     try {
//         $pdo_stmt = execute_sql_rtn_PDOStatement('SELECT ' . implode(', ', $column_arr) . ' FROM m_staff WHERE id=?', array($staff_id));
//         $mixed = $pdo_stmt->fetch(PDO::FETCH_ASSOC);
// // throw new Exception();
//     } catch (Exception $e) {
//         return READ_FAILED . LF;
//     }

    $id = -1;
    $last_name = NULL;
    $first_name = NULL;
    $last_name_kana = NULL;
    $first_name_kana = NULL;
    $sex = -1;
    $birthday = NULL;
    $privilege = NULL;
    $creator_id = -1;
    $created_date = NULL;
    $updater_id = -1;
    $updated_date = NULL;

    // mysqliのコンストラクタの例外用設定
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
//         $mysqli = new mysqli('localhost', 'root', '', 'y240608_01');

//         if ($mysqli->connect_error) {
//             return READ_FAILED . LF;
//         } else {
//             $mysqli->set_charset('utf8');
//         }

        $query =<<<EOQ
SELECT
  s.id AS id
  , s.last_name
  , s.first_name
  , s.last_name_kana
  , s.first_name_kana
  , s.sex
  , CONCAT(s.birth_year, '年', s.birth_month, '月', s.birth_day, '日') AS birthday
  , pr.privilege
  , s.creator_id
  , s.created_date
  , s.updater_id
  , s.updated_date
FROM
  m_staff_for_dev AS s INNER JOIN t_privilege_for_dev as pr on s.id=pr.id
WHERE
  s.id={$staff_id}
EOQ;

//         if ($stmt = $mysqli->prepare($query)) {
//             $stmt->bind_param('i', $staff_id);
//             $stmt->execute();
//             $stmt->bind_result($id
//                 , $last_name
//                 , $first_name
//                 , $last_name_kana
//                 , $first_name_kana
//                 , $sex
//                 , $birthday
//                 , $privilege
//                 , $creator_id
//                 , $created_date
//                 , $updater_id
//                 , $updated_date);

//             if (!$stmt->fetch()) {
//                 //TODO:変数にエラーメッセージ詰める(staff_idは存在しているため基本的にエラーはおきないが)
//             }

//             $stmt->close();
//         }

        $mysqli = new CmnMySqlI();
        $mixed = $mysqli->query($query);
        


    } catch (Exception $e) {
    }

    //TODO:選択肢と共通化
    $sex_arr = array('0' => '-', '1' => '男', '2' => '女', '3' => '未選択');
    $privilege_arr = array('O' => '一般', 'A' => '管理者');

    $row = NULL;

    foreach ($column_arr as $key => $value) {
        $processedValue = NULL;

        switch ($value) {
            case 'sex':
                $processedValue = $sex_arr[$mixed[$value]];
                break;

            case 'birthday':
                $split_arr = explode('-', $mixed[$value]);
                $processedValue = $split_arr[0] . '年'
                    . process_month_and_day($split_arr[1]) . '月'
                    . process_month_and_day($split_arr[2]) . '日（'
                    . get_age(str_replace('-', '', $mixed[$value])) . '歳）';
                break;

            case 'created_date':
            case 'updated_date':
                $processedValue = $mixed[$value];
                break;

            default:
                $processedValue = $mixed[$value];
                break;
        }


        $row .= '<tr><td>' . $key . '</td><td>：' . $processedValue . '</td></tr>' . LF;
    }

    return <<<EOC
<form method="post" action="staff_update_or_delete.php" onSubmit="return confirmDelete()">
<table>
<tr><td>スタッフID</td><td>{$id}</td></tr>
<tr><td>氏</td><td>{$last_name}</td></tr>
<tr><td>名</td><td>{$first_name}</td></tr>
<tr><td>氏（カナ）</td><td>{$last_name_kana}</td></tr>
<tr><td>名（カナ）</td><td>{$first_name_kana}</td></tr>
<tr><td>性別</td><td>{$sex_arr[$sex]}</td></tr>
<tr><td>生年月日</td><td>$birthday</td></tr>
<tr><td>権限</td><td>{$privilege_arr[$privilege]}</td></tr>
<tr><td>登録者ID</td><td>{$creator_id}</td></tr>
<tr><td>登録日時</td><td>{$created_date}</td></tr>
<tr><td>更新者ID</td><td>{$updater_id}</td></tr>
<tr><td>更新日時</td><td>{$updated_date}</td></tr>
</table>
<div class="m-t-1em sbmt"><input type="submit" name="staff_update" value="更新" onClick="sbmt_nm='staff_update'"><input type="submit" name="staff_delete" value="削除" onClick="sbmt_nm='staff_delete'"></div>
<input type="hidden" name="staff_id" value="{$id}">
</form>

EOC;
}



?>
