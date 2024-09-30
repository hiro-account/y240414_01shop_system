<?php
require_once 'const.php';

class MyDB {
    private $mysqli;
    private $mode;
    private $count;

    // コンストラクタ
    function __construct() {
        // DB接続
        $this->mysqli = new mysqli('localhost', 'root', EMPTY_STR, 'y240608_01');

        if ($this->mysqli->connect_error) {
        } else {
            $this->mysqli->set_charset('utf8');
        }
    }

    // デストラクタ
    function __destruct() {
        $this->mysqli->close();
    }

    function query($prm_query) {
        $result = $this->mysqli->query($prm_query);

        if ($result === FALSE) {
            $error = $this->mysqli->errno . ':' . $this->mysqli->error;

            return array ('status' => FALSE,
                'count' => I_0,
                'result' => EMPTY_STR,
                'error' => $error);
        }

        if ($result === TRUE) {
            // SELECT文以外
            return array ('status' => TRUE,
                'count' => $this->mysqli->affected_rows,
                'result' => EMPTY_STR,
                'error' => EMPTY_STR);
        } else {
            // SELECT文
            $num_rows = $result->num_rows;
            $data = array();

            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $result->close();

            return array('status' => TRUE,
                'count' => $num_rows,
                'result' => $data,
                'error' => EMPTY_STR);
        }
    }
}
?>
