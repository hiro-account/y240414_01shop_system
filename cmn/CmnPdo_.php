<?php

const DSN = 'mysql:dbname=y240608_01;host=localhost;charset=utf8';
const USERNAME = 'root';
const PASSWD = '';
const OPTIONS = NULL;

const EXCEPTION = 'exception';
const STMT = 'stmt';
const ROW_COUNT = 'row_count';

class CmnPdo {
    private $dbh;
    private PDOStatement $pdo_stmt;

    private $result_arr;

    function  __construct() {
        $this->result_arr = array(EXCEPTION => NULL, ROW_COUNT => NULL, STMT => NULL);
    }

    function prepare($prm_query) : PDOStatement {
        $dbh = new PDO(DSN, USERNAME, PASSWD, OPTIONS);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh->prepare($prm_query);
    }

    function execute($prm_arr = NULL) {
        try {
            if (isset($prm_arr)) {
                $this->pdo_stmt->execute($prm_arr);
            } else {
                $this->pdo_stmt->execute();
            }

            $this->result_arr[ROW_COUNT] = $this->pdo_stmt->rowCount();
            $this->result_arr[STMT] = $this->pdo_stmt;
        } catch (Exception $e) {
            $this->result_arr[EXCEPTION] = $e;
        }

        return $this->result_arr;
    }

    function  __destruct() {
        $this->dbh = NULL;
    }
}
?>
