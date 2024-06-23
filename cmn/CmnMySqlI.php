<?php
class CmnMySqlI {
    private $mysqli;

    // コンストラクタ
    function __construct() {
        // DB接続
        $this->mysqli = new mysqli('localhost', 'root', '', 'y240608_01');

        if ($this->mysqli->connect_error) {
        } else {
            $this->mysqli->set_charset('utf8');
        }
    }

    // デストラクタ
    function  __destruct() {
        if (isset($this->mysqli)) {
            $this->mysqli->close();
        }
    }

    function query($prm_query) {
        $mixed = $this->mysqli->query($prm_query);

        $errno = -1;
        $error = NULL;
        $rows = -1;
        $array = NULL;

        if ($mixed === FALSE) {
            $errno = $this->mysqli->errno;
            $error = $this->mysqli->error;
            $rows = 0;
        } elseif ($mixed === TRUE) {
            // SELECT文以外
            $rows = $this->mysqli->affected_rows;
        } else {
            // SELECT文
            $rows = $mixed->num_rows;
            $array = array();

            while ($arr = $mixed->fetch_assoc()) {
                $array[] = $arr;
            }
        }

        $mixed->close();

        return array('errno' => $errno, 'error' => $error, 'rows' => $rows, 'array' => $array);
    }
}
