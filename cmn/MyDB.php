<?php

class MyDB {
    private $mysqli;
    private $mode;
    private $count;
    
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
    function __destruct() {
        $this->mysqli->close();
    }
    
    function query($prm_query) {
        $result = $this->mysqli->multi_query($prm_query);
        
        if ($result === FALSE) {
            $error = $this->mysqli->errno . ':' . $this->mysqli->error;
            
            return array ('status' => FALSE,
                'count' => 0,
                'resutl' => '',
                'error' => $error);
        }
        
        if ($result === TRUE) {
            // SELECT文以外
            return array ('status' => TRUE,
                'count' => $this->mysqli->affected_rows,
                'result' => '',
                'error' => '');
        } else {
            // SELECT文
            
            
            
            
            
        }
        
        
        
        
        
        
        
        
    }
    
    
}





?>
