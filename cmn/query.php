<?php
require_once 'CmnPdo.php';

function execute_query($prm_query, $prm_prm_arr = NULL) {
    $cmn_pdo = new CmnPdo();
    $result_array_from_prepare = $cmn_pdo->prepare($prm_query);

    if (isset($result_array_from_prepare[EXCEPTION])) {
        return $result_array_from_prepare;
    }

    return $cmn_pdo->execute($prm_prm_arr);
}

?>
