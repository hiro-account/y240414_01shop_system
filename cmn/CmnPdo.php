<?php

const DSN = 'mysql:dbname=y240608_01;host=localhost;charset=utf8';
const USERNAME = 'root';
const PASSWD = '';
const OPTIONS = NULL;

class CmnPdo {
    private $dbh;

    function  __construct() {
    }

    function prepare($prm_query) : PDOStatement {
        $this->dbh = new PDO(DSN, USERNAME, PASSWD, OPTIONS);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->dbh->prepare($prm_query);
    }

    function  __destruct() {
        $this->dbh = NULL;
    }
}
?>
